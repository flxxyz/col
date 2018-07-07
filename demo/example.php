<?php

require_once '../vendor/autoload.php';

use Col\Again\DI;

function DI() {
    return new DI([
        'router' => function () {
            return new \Col\Again\Router();
        },
    ]);
}

$router = DI()->router;
$router->get();
var_dump();


