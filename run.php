<?php

define('DS', DIRECTORY_SEPARATOR, true);
define('SITE_DIR', realpath(__DIR__) . DS, true);
define('BASE_DIR', realpath(SITE_DIR) . DS, true);

require_once 'handle.php';
require_once 'vendor/flxxyz/col/src/Common/function.php';

/**
 * 启动websocket服务器
 * @param string host (default '0.0.0.0')
 * @param int port (default 9501)
 */
Chat::run('0.0.0.0', 9501);
