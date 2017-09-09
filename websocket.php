<?php

/**
 * type: 1
 *       2
 *       3
 *       4
 *       5
 */

ini_set('date.timezone', 'PRC');

$server = new swoole_websocket_server("0.0.0.0", 9501);

$user_list = [];

$server->on('open', function (swoole_websocket_server $server, $request) {
    //var_dump($request);
    global $user_list;
    $user_list[$request->fd] = [];
    echo message('info', "用户{$request->fd}连接成功\n", '1');
});

$server->on('message', function (swoole_websocket_server $server, $frame) {
    //var_dump($frame);
    $id = $frame->fd;
    $state = $frame->opcode;
    $data = json_decode($frame->data);
    $data->type = 'system';
    $data->message = "欢迎{$data->name}进入";
    var_dump($data);

    //var_dump($data);
    //echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, json_encode($data));
    var_dump($server);
});

$server->on('close', function ($server, $fd) {
    echo "client {$fd} closed\n";
});

$server->start();


function message($type, $content, $status) {
    $time = date('Y-m-d H:i:s');
    return $time. ' [' . strtoupper($type) . ']: ' . $content;
}