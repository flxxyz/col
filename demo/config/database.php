<?php

return [
    'database' => [
        'driver'   => 'mysql',
        'host'     => 'localhost',
        'database' => 'test',
        'username' => 'root',
        'password' => '',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ],
];