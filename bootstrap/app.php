<?php

if (!APP_DEBUG)
    error_reporting(0);

register_shutdown_function("fatal_handler");
set_error_handler("error_handler");

define('E_FATAL', E_ERROR | E_USER_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_PARSE);

function fatal_handler()
{
    $error = error_get_last();
    if ($error && ($error["type"] === ($error["type"] & E_FATAL))) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];
        error_handler($errno, $errstr, $errfile, $errline);
    }
}

function error_handler($errno, $errstr, $errfile, $errline)
{
    $error = "{$errstr}";
    $error .= "<br>file: <strong>{$errfile}</strong>";
    $error .= "<br>line: <strong>{$errline}</strong>";
    $str = "error_code: <strong>{$errno}</strong><br>" . $error;
    exit($error);
}

require_once BASE_DIR . 'vendor/autoload.php';

$core = Col\Core::instance();
$core->request = Col\Request::instance();
$core->route = Col\Route::instance($core->request);
$core->session = Col\Session::make(config('session'));
$route = $core->route;

require_once BASE_DIR . 'route/web.php';

$route->end();
