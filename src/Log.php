<?php

namespace Chat;

/**
 * Class Log
 *
 * @package Log
 * @method static notice($channel, ...$message)
 * @method static info($channel, ...$message)
 * @method static debug($channel, ...$message)
 * @method static warn($channel, ...$message)
 * @method static error($channel, ...$message)
 */
class Log
{
    const METHODS = [
        'notice' => '[notice]',
        'info' => '[info  ]',
        'debug' => '[debug ]',
        'warn' => '[warn  ]',
        'error' => '[error ]',
    ];

    protected static $path = '';

    public static function setPath($path)
    {
        self::$path = realpath($path).DIRECTORY_SEPARATOR;
    }

    private static function isPath($channel)
    {
        $date = date('Y-m-d/');
        if (!is_dir(self::$path.$date)) {
            mkdir(self::$path.$date, 0777, true) or 'mkdir fail';
        }

        $filename = self::$path.$date.$channel.'.log';
        if (!is_file($filename)) {
            if (!touch($filename)) {
                echo self::message('warn', 'SYSTEM', '日志文件创建失败，请检查目录权限');

                return false;
            }
        }

        return $filename;
    }

    private static function message($method, $channel = '', $param = [])
    {
        return join('', [
            date('Y-m-d H:i:s'),
            ' ',
            self::METHODS[$method],
            ' ',
            $channel,
            ' ',
            json_encode($param),
            PHP_EOL,
        ]);
    }

    private static function handler($method, $channel, $contents)
    {
        if ($filename = self::isPath($channel)) {
            foreach ($contents as $content) {
                $str = self::message($method, $channel, $content);
                file_put_contents($filename, $str, FILE_APPEND);
                echo $str;
            }
        }
    }

    public static function __callStatic($method, $params)
    {
        ([self::class, 'handler'])($method, $params[0],
            array_slice($params, 1));
    }
}
