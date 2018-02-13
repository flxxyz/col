<?php

return [
    'defalut' => 'mysql',
    'mysql'   => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'database' => 'test',
        'username' => 'test_user',
        'password' => 'test_pass',
        'charset'  => 'utf8mb4',
        'perfix'   => 'down_',
        'options'  => [
            /**
             * @link http://php.net/manual/zh/pdo.error-handling.php
             */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],
    'redis'   => [
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'pass'     => null,
        'database' => 0,
    ],
    'file'    => [
        'path'   => realpath(BASE_DIR . 'config/db'),
        'format' => 'json',
    ],
];