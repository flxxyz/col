<?php

namespace Col;

/**
 * 载入公用函数
 */
require_once __DIR__ . '/Common/function.php';

use Col\Exceptions\InvalidRouteArgumentException;

class Core
{
    public $container;

    public function __construct()
    {
        $this->container = new Container([
            'router' => function () {
                return new Route;
            },
            'response' => function () {
                return new Response;
            },
        ]);
    }

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @param Container $container
     */
    public function setContainer(Container $container): void
    {
        $this->container = $container;
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

    public function respond($response)
    {
        if (is_null($response)) {
            $response = $this->container->response->text('');
        } else if (!$response instanceof Response) {
            $response = $this->container->response->text($response);
        }

        return $response->getBody();
    }

    public function bind($key, Callable $callable)
    {
        $this->container[$key] = $callable;
    }

    public function response()
    {
        return $this->container->response;
    }

    public function __get($property)
    {
        return $this->container[$property];
    }
}