<?php

/**
 * type: 1
 *       2
 *       3
 *       4
 *       5
 */

ini_set('date.timezone', 'PRC');

$tcp_table = new swoole_table(1024);
$tcp_table->column('fd', swoole_table::TYPE_INT);
$tcp_table->create();

$user_list = [];

$server = new swoole_websocket_server("0.0.0.0", 9501);
$server->table = $tcp_table;
$server->users = $user_list;

$server->on('open', function (swoole_websocket_server $server, $request) {
    global $user_list;
    $server->users[$request->fd] = ['fd' => $request->fd];
    $server->table->set($request->fd, ['fd' => $request->fd]); //获取客户端id插入table
    echo message('info', "用户{$request->fd}连接成功\n", '1');
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    $fd = $frame->fd;
    $state = $frame->opcode;
    $data = json_decode($frame->data);
    $id = $data->id;
    $name = $data->name;
    $sex = $data->sex;
    $icon = $data->icon;

    $data = [];
    if($data->type == 'init') {
        $type = 'tips';
        $data['message'] = "欢迎{$name}进入";
    } else if($data->type == 'message') {
        $type = 'message';
        $user = $server->users[$fd]['info'];

    } else {
        $type = 'other';
    }
    $data = ['type' => $type];

    // 保留用户信息
    foreach($server->table as $u) {
        if(!isset($server->users[$u['fd']]['info'])) {
            $server->users[$u['fd']]['info'] = [
                'id' => $id,
                'name' => $name,
                'sex' => $sex,
                'icon' => $icon,
                ];
        }
    }

    foreach($server->table as $u) {
        $server->push($u['fd'], json_encode($data));//消息广播给所有客户端
    }
});

$server->on('close', function ($server, $fd) {
    $server->table->del($fd);

    foreach($server->table as $u) {
        if(isset($u['fd'])) {
            $server->push($u['fd'], json_encode([
                'type' => 'tips',
                'name' => 'aaa',
                'message' => "用户{$fd}退出",
                ]));//消息广播给所有客户端
        }
    }
    echo message('info', "用户{$fd}退出\n", '1');
});

$server->start();

function message ($type, $content, $status)
{
    $time = date('Y-m-d H:i:s');
    return $time . ' [' . strtoupper($type) . ']: ' . $content;
}