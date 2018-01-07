<?php

if (!APP_DEBUG)
    error_reporting(0);

$app->bind('config', function () {
    $config = config('database');
    return $config;
});
# array implementation of $app->bind (Callable or call_user_func)
$app->bind('databaseConnection', [new Col\Database\Connection, 'make']);
$app->bind('database', function ($app) {
    return new Col\Database\QueryBuilder($app->databaseConnection);
});
// or bind anything to the app container..
$app->bind('foo', function () {
    return 'bar';
});
