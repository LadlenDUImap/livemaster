<?php

namespace app\core;

/**
 * Config служит для работы с конфигурацией.
 *
 * @package core
 */
class Config extends \app\base\Singleton
{
    protected static $config;

    public function __get($name)
    {
        return self::$config[$name];
    }

    /**
     * Возвращает данные конфигурации.
     *
     * @return array Массив с данными конфигурации.
     */
    public static function inst()
    {
        if (!self::$config)
        {
            self::$config = require_once(APP_DIR . 'config/app.php');
        }

        return parent::inst();
    }
}

