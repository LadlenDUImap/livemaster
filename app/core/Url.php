<?php

namespace app\core;

class Url
{
    /** @var  string URL путь к корневой директории (слеш на конце) */
    protected static $webRootPath;


    public static function getWebRootPath(): string
    {
        if (!self::$webRootPath) {
            self::$webRootPath = str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
        }

        return self::$webRootPath;
    }

    public static function to(string $path): string
    {
        $path = trim($path);

        if (isset($path[0]) && $path[0] == '/') {
            if (!isset($path[1]) || $path[1] != '/') {
                return self::getWebRootPath() . substr($path, 1);
            }
        }

        return $path;
    }
}
