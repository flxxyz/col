<?php

namespace Col;

use Col\Core;

abstract class Controller
{
    private $app;
    private $view;

    public function __construct(\Col\Core $app)
    {
        $this->app = $app;
    }

    protected function app()
    {
        return $this->app;
    }
}