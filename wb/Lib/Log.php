<?php

namespace Lib;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;


class Log
{
    /**
     * 输出info类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    private static function info($channel, $message, $param)
    {
        echo self::message('INFO', $message, $param);
        $log = new Logger($channel);
        $channel = self::is_log($channel);
        $log->pushHandler(new StreamHandler($channel), Logger::INFO);
        $log->info($message, $param);
    }

    /**
     * 输出notice类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    private static function notice($channel, $message, $param)
    {
        echo self::message('NOTICE', $message, $param);
        $log = new Logger($channel);
        $channel = self::is_log($channel);
        $log->pushHandler(new StreamHandler($channel), Logger::NOTICE);
        $log->notice($message, $param);
    }

    /**
     * 输出debug类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    private static function debug($channel, $message, $param)
    {
        echo self::message('DEBUG', $message, $param);
        $log = new Logger($channel);
        $channel = self::is_log($channel);
        $log->pushHandler(new StreamHandler($channel), Logger::DEBUG);
        $log->debug($message, $param);
    }

    /**
     * 输出warn类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    private static function warn($channel, $message, $param)
    {
        echo self::message('WARNING', $message, $param);
        $log = new Logger($channel);
        $channel = self::is_log($channel);
        $log->pushHandler(new StreamHandler($channel), Logger::WARNING);
        $log->warn($message, $param);
    }

    /**
     * 输出err类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    private static function err($channel, $message, $param)
    {
        echo self::message('ERROR', $message, $param);
        $log = new Logger($channel);
        $channel = self::is_log($channel);
        $log->pushHandler(new StreamHandler($channel), Logger::ERROR);
        $log->err($message, $param);
    }

    /**
     *
     * @param string $action
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    public static function handle(string $action = 'info', string $channel = '', string $message = '', array $param = []) {
        if(!method_exists(self::class, $action)) {
            echo self::message('ERROR', '方法不存在！');
        }else {
            $action = strtolower($action);
            self::$action($channel, $message, $param);
        }
    }

    /**
     * 命令行输出消息
     * @param $type
     * @param string $message
     * @param array $param
     * @return false|string
     */
    private static function message($type, $message = '', $param = [])
    {
        $str = date('Y-m-d H:i:s');
        $str .= " [{$type}] {$message} ";
        $str .= json_encode($param);
        $str .= "\n";
        return $str;
    }

    private static function is_log(string $channel) {
        if(!is_file('log/' . $channel . '.log')) {
            if(!touch('log/' . $channel . '.log')) {
                echo self::message('WARNING', '日志文件创建失败，请检查目录权限');
            }
        }

        return 'log/' . $channel . '.log';
    }
}