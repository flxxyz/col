<?php

namespace App\Controller;

class IndexController
{
    public function index()
    {
        echo 'success';
    }

    public function demo()
    {
        echo '<pre>';
        session()->set(['abc' => '123']);

        _e(session()->get('abc'));
        echo '<br>';
        _e(session()->all());
    }
}