<?php

namespace Col;

/**
 * 载入公用函数
 */
require_once __DIR__ . '/Common/function.php';

use Col\{
    Route,
    Response,
    Controller,
    Exceptions\InvalidRouteArgumentException
};

class Core
{
    public function __construct()
    {
        $this->container = new Container([
            'router' => function () {
                return new Route;
            },
            'response' => function () {
                return new Response;
            }
        ]);
    }

    public function get($uri, $handler)
    {
        $this->container->router->addRoute($uri, $handler, 'GET');
    }

    public function post($uri, $handler)
    {
        $this->container->router->addRoute($uri, $handler, 'POST');
    }

    public function run()
    {
        $router = $this->container->router;
        $router->setPath($_SERVER['PATH_INFO'] ?? '/');
        $router->setAction($_SERVER['REQUEST_METHOD'] ?? '');
        $handler = $router->getHandler();
        $response = $this->route($handler);
        echo $this->respond($response);
    }

    public function respond($response)
    {
        if(is_null($response)) {
            $response = $this->container->response->text('');
        }else if (!$response instanceof Response) {
            // $response = $this->response()->text($response); // another way of looking at it..
            $response = $this->container->response->text($response);
        }

        return $response->getBody();
    }

    public function route($handler)
    {
        if (is_array($handler)) {
            $class = "\\App\\Controller\\{$handler[0]}";
            $handler[0] = new $class($this);
        }
        if (!is_callable($handler)) {
            throw new InvalidRouteArgumentException;
        }
        return call_user_func($handler, $this);
    }

    public function bind($key, Callable $callable)
    {
        $this->container[$key] = $callable;
    }

    public function response()
    {
        return $this->container->response;
    }

    public function view(string $view, $data)
    {
        return $this->container->response->view($view, $data);
    }

    public function __get($property)
    {
        return $this->container[$property];
    }
}