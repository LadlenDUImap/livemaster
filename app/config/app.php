<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'components' => [
        'db' => $db,
        'log' => [
            'class' => 'app\core\Log',
        ],
        'csrf' => [
            'class' => 'app\core\Csrf',
            'token_name' => '_csrf',
            'token_salt' => 'uIlmkI873d+)$7',
        ],
    ],
    'params' => $params,
];

return $config;
