<?php

namespace app\core;

/**
 * Router вызывает метод контроллера анализируя путь URL, который указывается в параметре $_GET['r']
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
        $route = $_GET['r'] ?? false;

        $contr = $this->getControllerPathFromUrlPath($route);
        $action = $this->getActionPartFromUrlPath($route);

        $class = '\\app\\controllers\\Controller';
        $action = 'action404';

        $path = '/app/controllers';

        if (!empty($_GET['r']))
        {
            $route = strtolower(trim($_GET['r'], '/\\'));
            $path .= '/' . $route;
        }

        $path .= '/Controller';

        $file = APP_DIR . '..' . $path . '.php';
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

        $controller = new $class;

        Lm::inst()->setController($controller);

        echo $controller->$action();
    }

    protected function getActionPartFromUrlPath($urlPath)
    {
        $action = 'index';

        if ($urlPath) {
            $parts = explode('/', $urlPath);
            if ($partsCount = count($parts)) {
                if ($parts[$partsCount - 1]) {
                    // что-то есть в конце - значит это действие, не по-умолчанию
                    $action = $parts[$partsCount - 1];
                }
            }
        }

        return $action;
    }

    protected function getControllerPathFromUrlPath($urlPath)
    {
        $controllerPath = '';

        if ($urlPath) {
            if ($lastSlashPos = strrpos($urlPath, '/')) {
                $controllerPath = substr($urlPath, 0, $lastSlashPos);
            }
        }

        return $controllerPath;
    }

    /*protected function makePhpActionNameFromUrlPart($urlActionPart)
    {
        strtolower($urlActionPart);
        return;
    }*/
}