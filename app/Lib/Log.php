<?php

namespace Chat\Lib;

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
    static function info(string $channel, string $message, array $param = [])
    {
        echo self::message('INFO', $message, $param);
        $log = new Logger($channel);
        $log->pushHandler(new StreamHandler('log/' . $channel . '.log'), Logger::INFO);
        $log->info($message, $param);
    }

    /**
     * 输出notice类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    static function notice(string $channel, string $message, array $param = [])
    {
        echo self::message('NOTICE', $message, $param);
        $log = new Logger($channel);
        $log->pushHandler(new StreamHandler('log/' . $channel . '.log'), Logger::NOTICE);
        $log->notice($message, $param);
    }

    /**
     * 输出debug类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    static function debug(string $channel, string $message, array $param = [])
    {
        echo self::message('DEBUG', $message, $param);
        $log = new Logger($channel);
        $log->pushHandler(new StreamHandler('log/' . $channel . '.log'), Logger::DEBUG);
        $log->debug($message, $param);
    }

    /**
     * 输出warn类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    static function warn(string $channel, string $message, array $param = [])
    {
        echo self::message('WARNING', $message, $param);
        $log = new Logger($channel);
        $log->pushHandler(new StreamHandler('log/' . $channel . '.log'), Logger::WARNING);
        $log->warn($message, $param);
    }

    /**
     * 输出err类型消息
     * @param string $channel
     * @param string $message
     * @param array $param
     */
    static function err(string $channel, string $message, array $param = [])
    {
        echo self::message('ERROR', $message, $param);
        $log = new Logger($channel);
        $log->pushHandler(new StreamHandler('log/' . $channel . '.log'), Logger::ERROR);
        $log->err($message, $param);
    }

    /**
     * 命令行输出消息
     * @param $type
     * @param string $message
     * @param array $param
     * @return false|string
     */
    private static function message($type, string $message = '', array $param = [])
    {
        $str = date('Y-m-d H:i:s');
        $str .= " [{$type}] {$message} ";
        $str .= json_encode($param);
        $str .= "\n";
        return $str;
    }
}