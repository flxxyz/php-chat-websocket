<?php

/**
 * type: 1
 *       2
 *       3
 *       4
 *       5
 */

ini_set('date.timezone', 'PRC');

$host = '0.0.0.0';
$port = '9501';
$server = new swoole_websocket_server($host, $port);
$user_list = [];
$server->users = $user_list;

$server->on('open', function (swoole_websocket_server $server, $request) {
    global $user_list;
    $server->users[$request->fd] = ['fd' => $request->fd];  //获取客户端id插入table
    echo message('info', "第{$request->fd}个连接, 创建成功\n");
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    $fd = $frame->fd;
    $state = $frame->opcode;
    $data = json_decode($frame->data);
    $id = isset($data->id) ? $data->id : '';
    $name = isset($data->name) ? $data->name : '';
    $sex = isset($data->sex) ? $data->sex : '';
    $icon = isset($data->icon) ? $data->icon : '';
    $message = isset($data->message) ? $data->message : '';

    $result = [];

    if($data->type == 'init') {
        $type = 'tips';
        $result['message'] = "欢迎{$name}进入";
    } else if($data->type == 'message') {
        $type = 'message';
        $user = $server->users[$fd]['info'];
        $result['id'] = $user['id'];
        $result['name'] = $user['name'];
        $result['sex'] = $user['sex'];
        $result['icon'] = $user['icon'];
        $result['message'] = $message;
        echo message('message', "{$user['name']}: {$message}\n");
    } else {
        $type = 'other';
    }
    $result['type'] = $type;

    // 保留用户信息
    foreach($server->users as $u) {
        if(!isset($server->users[$u['fd']]['info'])) {
            $server->users[$u['fd']]['info'] = [
                'id' => $id,
                'name' => $name,
                'sex' => $sex,
                'icon' => $icon,
                ];
        }
    }

    foreach($server->users as $u) {
        $server->push($u['fd'], json_encode($result));//消息广播给所有客户端
    }
});

$server->on('close', function ($server, $fd) {
    foreach($server->users as $u) {
        if(isset($u['fd'])) {
            $user = $server->users[$fd]['info'];
            $server->push($u['fd'], json_encode([
                'type' => 'tips',
                'name' => $user['name'],
                'message' => "用户{$user['name']}退出",
                ]));//消息广播给所有客户端(除自己)
        }
    }

    unset($server->users[$fd]);  // 清除用户信息

    echo message('info', "用户{$user['id']}退出\n", '1');
});

echo message('system', "聊天服务器开启 运行在{$host}:{$port}\n");
$server->start();

function message ($type, $content)
{
    $time = date('Y-m-d H:i:s');
    $type = strtoupper($type);
    $data = "{$time} [{$type}]: {$content}";

    $dir = __DIR__ . '/log/';
    $filename = date('Y-m-d') . '.log';
    file_put_contents($dir . $filename, $data, FILE_APPEND);

    return $data;
}