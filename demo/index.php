<?php

// 是否开启debug模式
define('APP_DEBUG', true, true);

define('DS', DIRECTORY_SEPARATOR, true);
define('APP_DIR', realpath(__DIR__) . DS, true);
define('BASE_DIR', APP_DIR, true);

require_once '../vendor/autoload.php';

$app = new Col\Core;

require './bootstrap.php';

require './route.php';

$app->run();
