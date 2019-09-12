<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'components' => [
        'db' => $db,
        /*'test' => [
            'class' => 'app\test\Txt',
            'var1' => 'this is "var1"',
            'var2' => 'this is "var2"',
            'var3' => [
                'this is "var3"'
            ],
            'db2' => [
                'class' => 'app\core\db\Pdo',
                'dsn' => 'db dsn',
                'username' => 'db root',
                'password' => 'db temp12345',
                'charset' => 'db utf8md4',
                'db3' =>
                    [
                        'class' => 'app\test\Txt',
                        'aVar' => 'aVarVal',
                        'aVar2' => 'aVar2Val',
                    ],
            ],
        ],*/
    ],
    'params' => $params,
];

return $config;
