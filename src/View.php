<?php

namespace Col;

class View
{
    public $container;

    public function __construct()
    {
        $this->container = new Container([
            'response' => function () {
                return new Response;
            }
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

    /**
     * @param string $view
     * @param $data
     * @return mixed
     */
    public function put(string $view, $data)
    {
        return $this->container->response->view($view, $data);
    }

    /**
     * @param $property
     * @return mixed|null
     */
    public function __get($property)
    {
        return $this->container[$property];
    }
}