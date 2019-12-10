<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'components' => [
        'db' => $db,
        'log' => [
            'class' => 'app\core\Log',
            'log_file_prefix' => APP_DIR . 'runtime/log/php-livemaster',
        ],
        'csrf' => [
            'class' => 'app\core\Csrf',
            'token_name' => '_csrf',
            'token_salt' => 'uIlmkI873d+)$7',
        ],
        'web' => [
            'class' => 'app\core\Web',
            'token_name' => '_csrf',
            'token_salt' => 'uIlmkI873d+)$7',
        ],
    ],
    'params' => $params,
];

return $config;
