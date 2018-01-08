<?php

namespace App\Controller;

use Col\Util;

class AboutController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'about',
            'name' => 'Col',
            'description' => 'This is a simple PHP framework'
        ];

        view('index', $data);
    }
}