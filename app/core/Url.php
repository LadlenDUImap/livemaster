<?php

namespace app\core;

class Url extends \app\base\Component
{
    /** @var  string URL путь к корневой директории (слеш на конце) */
    protected $webRootPath;


    public function __construct()
    {
        $this->webRootPath = str_replace('web/index.php', '', $_SERVER['SCRIPT_NAME']);
    }

    public function getWebRootPath(): string
    {
        return $this->webRootPath;
    }

    public function to(string $path): string
    {
        $path = trim($path);

        if (isset($path[0]) && $path[0] == '/') {
            if (!isset($path[1]) || $path[1] != '/') {
                return $this->getWebRootPath() . substr($path, 1);
            }
        }

        return $path;
    }
}
