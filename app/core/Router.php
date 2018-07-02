<?php

namespace app\core;

/**
 * Router вызывает метод контроллера анализируя путь URL, который указывается в параметре $_GET['route']
 * - это путь к контроллеру, и параметр $_GET['action'] - это название дейтсвия.
 *
 * @package core
 */
class Router
{
    /**
     * Вызывает действие, генерирующее страницу, и выводит страницу.
     */
    public function run()
    {
        $class = '\\app\\controllers\\Controller';
        $action = 'action404';

        $path = '/app/controllers';

        if (!empty($_GET['route']))
        {
            $route = strtolower(trim($_GET['route'], '/\\'));
            $path .= '/' . $route;
        }

        $path .= '/Controller';


        $file = \app\core\Config::inst()->appDir . '..' . $path . '.php';
        if (file_exists($file))
        {
            require($file);
            $classTest = str_replace('/', '\\', $path);
            if (class_exists($classTest, false))
            {
                $action = !empty($_GET['action']) ? $_GET['action'] : 'index';
                $action = 'action' . ucfirst(strtolower($action));
                if (is_callable([$classTest, $action]))
                {
                    $class = $classTest;
                }
                else
                {
                    $class = '\\app\\controllers\\Controller';
                    $action = 'actionNoAction';
                }
            }
        }

        echo (new $class)->$action();
    }
}