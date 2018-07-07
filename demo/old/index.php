<?php

// 是否开启debug模式
define('APP_DEBUG', true, true);

define('DS', DIRECTORY_SEPARATOR, true);
define('APP_DIR', realpath(__DIR__) . DS, true);
define('BASE_DIR', realpath(APP_DIR . '..') . DS, true);

include_once 'bootstrap.php';
