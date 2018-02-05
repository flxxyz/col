<?php

namespace App\Controller;

class IndexController extends Controller
{
    public function index()
    {
        echo 'success';
    }

    public function db()
    {
        $start = get_microtime();
        $rows = DB('goods')->where('id > ?', '0');
        $end = get_microtime();
        echo round(($end-$start) * 1000, 2).'ms';
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