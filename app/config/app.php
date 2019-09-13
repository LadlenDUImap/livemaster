<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'components' => [
        'db' => $db,
        'log' => [
            'class' => 'app\core\Log',
        ],
    ],
    'params' => $params,
];

return $config;
