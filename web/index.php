<?php

if (version_compare(phpversion(), '7.1', '<') == true) {
    die('Версия PHP должна быть не ниже 7.1');
}

define('LM_DEBUG', true);

#define('WEB_DIR', __DIR__ . '/../');
define('APP_DIR', realpath(__DIR__ . '/../app') . '/');

ini_set('log_errors', 1);
ini_set('error_log', APP_DIR . 'runtime/log/php-livemaster-error.log');
error_reporting(E_ALL);

if (LM_DEBUG) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}

$config = require APP_DIR . 'config/app.php';

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

    require $fileName;
});

\app\core\Lm::inst()->run($config);
