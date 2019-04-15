<?php

namespace Chat\Server;


use Swoole\Mysql\Exception;

class Master
{
    public $host = '0.0.0.0';
    public $port = 9501;

    private $list = [];

    private $serve;

    private static $instance = null;

    public static function make($host, $port)
    {
        if (is_null(self::$instance)) {
            return new self($host, $port);
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->setHost(func_get_args()[0] ?? $this->host);
        $this->setPort(func_get_args()[1] ?? $this->port);
        $this->serve = new \swoole_websocket_server($this->host, $this->port);
//        $this->redis = new \Redis();
//        $this->redis->connect('127.0.0.1', 6379);
    }

    public function run() {
        $this->handle('open');
        $this->handle('message');
        $this->handle('close');
        $this->handle('request');

        // 记录系统
        Log::handle('info', 'system', 'websocket服务器运行在', ['host' => $this->host, 'port' => $this->port]);
        $this->serve->start();
    }

    private function handle($action) {
        if(!method_exists($this, $action)) {
            echo '方法不存在' . PHP_EOL;
        }else {
            $this->serve->on($action, $this->$action);
        }
    }

    private function open(\swoole_websocket_server $server, $request) {
        /*
             * @param object $request
             * [
             *   fd => []
             *   header => []
             *   server => []
             *   request => ?
             *   cookie => []
             *   get => ?
             *   files => ?
             *   post => ?
             *   tmpfiles => ?
             * ]
             */
        $fd = $request->fd;
        $this->redis->hSet('Users', $fd, $this->json([
            'userInfo' => [],
            'message' => [],
        ]));

        // 记录创建链接数
        Log::handle('info', date('Ymd'), "第{$fd}个连接, 创建成功");
    }

    private function message(\swoole_websocket_server $server, $frame) {
        $fd = $frame->fd;
        $data = json_decode($frame->data, true);
        $new_data = $data;
        unset($new_data['type']);

        switch ($data['type']) {
            case 'init':
                $User = $this->redis->hGet('Users', $fd);
                $User = json_decode($User, true);

                $User['userInfo'] = $new_data;

                $this->redis->hSet('Users', $fd, $this->json($User));

                $data = [
                    'message' => "欢迎{$new_data['name']}进入",
                    'type' => 'tips'
                ];
                break;
            case 'message':
                $User = $this->redis->hGet('Users', $fd);
                $User = json_decode($User, true);

                $new_data['time'] = microtime(true);
                $User['message'][] = $new_data;

                $this->redis->hSet('Users', $fd, $this->json($User));

                $data['id'] = $User['userInfo']['id'];
                $data['name'] = $User['userInfo']['name'];
                $data['sex'] = $User['userInfo']['sex'];
                $data['icon'] = $User['userInfo']['icon'];

                // 记录用户发送消息
                $message = "{$User['userInfo']['name']}: {$data['message']}";
                Log::handle('notice', date('Ymd'), $message);
                break;
            default:
                $data['type'] = 'other';
                break;
        }
        unset($User, $new_data);

        // 广播所有用户
        foreach ($this->serve->connections as $fd) {
            $this->serve->push($fd, json_encode($data, JSON_UNESCAPED_UNICODE));
        }
    }

    private function close($ser, $fd) {
        $User = $this->redis->hGet('Users', $fd);
        $User = json_decode($User, true);
        $name = $User['userInfo']['name'];
        $this->redis->hDel('Users', $fd);

        $data = [
            'type'    => 'tips',
            'name'    => $name,
            'message' => " {$name} 退出",
        ];

        // 广播所有用户
        foreach ($this->serve->connections as $nfd) {
            var_dump($this->serve->connections);
            if($fd == $nfd) {
                continue;
            }

            $this->serve->push($nfd, json_encode($data, JSON_UNESCAPED_UNICODE));
        }

        // 记录用户退出
        $message = "用户{$name}退出";
        Log::handle('info', date('Ymd'), $message);
    }

    private function request(\swoole_http_request $request, \swoole_http_response $response) {
        $get = json_decode($request->get['message'], true);
        if(is_null($get)) {
            return null;
        }

        // 记录外部插入消息
//            $this->redis->hSet('System', 'sys', $get);

        // 广播所有用户
        foreach ($this->serve->connections as $fd) {
            try {
                $this->serve->push($fd, $request->get['message']);
            }catch (Exception $e) {
                echo '消失的链接';
            }
        }
    }

    private function json($data) {
        return json_encode($data, JSON_UNESCAPED_UNICODE);
    }

}
