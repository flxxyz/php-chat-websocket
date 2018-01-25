<?php

// 是否开启debug模式
define('APP_DEBUG', true, true);

define('DS', DIRECTORY_SEPARATOR, true);
define('SITE_DIR', realpath(__DIR__) . DS, true);
define('APP_DIR', realpath(SITE_DIR . '../app') . DS, true);
define('BASE_DIR', realpath(SITE_DIR . '..') . DS, true);

require_once BASE_DIR . 'bootstrap/app.php';
function url($path = '')
{
    $config = config('config');
    $url = $config['url'] . $path;
    header("Location: {$url}");
}
