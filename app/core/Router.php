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
        $gotRoute = $_GET['r'] ?? false;

        $class = '\\app\\controllers\\Controller';
        $action = 'action404';

        $path = '/app/controllers/' . strtolower($this->getControllerPathFromUrlPath($gotRoute));
        $path = rtrim($path, '/') . '/Controller';

        $file = realpath(APP_DIR . '..' . $path . '.php');

        // Защита от взлома через относительные пути
        if ($file && strpos($file, APP_DIR . 'controllers/') === 0)
        {
            require($file);
            $classTest = str_replace('/', '\\', $path);
            if (class_exists($classTest, false))
            {
                $action = $this->makePhpActionNameFromUrlPart($this->getActionPartFromUrlPath($gotRoute));
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

        Web::sendHtmlResponse($controller->$action());
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

    protected function makePhpActionNameFromUrlPart($urlActionPart)
    {
        $action = 'action' . implode('', array_map('ucfirst', explode('-', strtolower($urlActionPart))));
        return $action;
    }
}