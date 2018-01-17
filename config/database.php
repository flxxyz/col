<?php

return [
    'database' => [
        'driver'   => 'mysql',
        'host'     => '119.28.14.116',
        'port'     => 3306,
        'database' => 'csk',
        'username' => 'csk',
        'password' => 'zWLnnrEN76',
        'options'  => [
            /**
             * @link http://php.net/manual/zh/pdo.error-handling.php
             */
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ],
    ],
];