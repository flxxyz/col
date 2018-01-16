<?php

if (!APP_DEBUG)
    error_reporting(0);

require_once '../vendor/autoload.php';

$core = Col\Core::instance();
$core->request = Col\Request::instance();
$core->route = Col\Route::instance($core->request);
$route = $core->route;

require_once APP_DIR . 'route.php';

$route->end();
