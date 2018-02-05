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
        $good = DB('goods');
        $rows = $good->where('id > ?', '0');
        $good = get_array($rows);
        $end = get_microtime();
        echo round(($end-$start) * 1000, 2).'ms';
        echo '<pre>';
        print_r($good);
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