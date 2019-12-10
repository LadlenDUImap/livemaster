<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'components' => [
        'db' => $db,
        'log' => [
            'class' => 'app\core\Log',
            'log_file_prefix' => LM_TEST_DIR . 'runtime/log/php-livemaster',
        ],
        'csrf' => [
            'class' => 'app\core\Csrf',
            'token_name' => '_csrf',
            'token_salt' => 'uIlmkI873d+)$7',
        ],
        'web' => [
            'class' => 'app\core\Web',
        ],
        'url' => [
            'class' => 'app\core\Url',
        ],
    ],
    'params' => $params,
];

return $config;
