<?php

namespace App\Controller;

class AboutController extends Controller
{
    public function index()
    {
        $data = [
            'name' => 'Col',
            'description' => 'This is a simple PHP framework'
        ];
        return $this->app()->view('index', $data);
    }
}