<?php
require_once 'vendor/autoload.php';

$wb = new \Lib\WebSocket('127.0.0.1', 9501);
$wb->run();


