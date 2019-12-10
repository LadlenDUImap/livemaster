<?php

//namespace app\base;

/**
 * Singleton помогает превратить класс в singleton.
 *
 * @package helpers
 */
abstract class Singleton
{
    private static $_instances = [];

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    protected function __wakeup()
    {
        //throw new Exception(_('Cannot unserialize singleton'));
    }

    /**
     * Возвращает экземпляр синглтона.
     *
     * @return mixed
     */
    public static function inst()
    {
        $cls = get_called_class();
        if (!isset(self::$_instances[$cls]))
        {
            self::$_instances[$cls] = new static;
        }
        return self::$_instances[$cls];
    }
}