<?php

namespace Col\Again;


class Router
{
    protected $routes = [];

    public function add($uri, $handler, $method)
    {
        $slash_found = preg_match('/^\//', $uri);
        if (!$slash_found) {
            $uri = '/' . $uri;
        }
        $this->routes[$uri][$method] = $handler;
    }
}