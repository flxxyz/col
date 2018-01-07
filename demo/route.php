<?php

$app->get('/', function () {
    return '<h1>hello Col</h1>';
});

$app->get('about', ['AboutController', 'index']);
