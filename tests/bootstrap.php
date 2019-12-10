<?php

//define('LM_TEST_DIR', __DIR__ . '/');

define('LM_DEBUG', true);
define('APP_DIR', __DIR__ . '/');

define('LM_GLOBAL_CONFIG', require APP_DIR . 'config/app.php');

spl_autoload_extensions('.inc,.php');
set_include_path(get_include_path() . PATH_SEPARATOR . realpath(__DIR__ . '/../'));

// PSR-0
spl_autoload_register(function ($className) {
    $className = ltrim($className, '\\');
    $fileName = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if (is_file($fileName)) {
        require $fileName;
    }
});
