<?php

require_once '../../vendor/autoload.php';

use Chat\Server\WebsocketHandler;
use swoole_websocket_and_tcp_and_udp\Server;
use Chat\Log;

Log::setPath(__DIR__.'/../log/');

$config = [
    'timezone' => 'Asia/Shanghai',
    'tick_interval_timer' => 30, //秒
    'websocket' => [
        'enable' => true,
        'host' => '0.0.0.0',
        'port' => '9000',
        'type' => SWOOLE_SOCK_TCP,
        'setting' => [
            'daemonize' => false,
            'task_worker_num' => 1,
        ],
        'handler' => WebsocketHandler::class,
    ],
];
$server = new Server($config);
$server->run();
