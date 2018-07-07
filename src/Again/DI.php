<?php

namespace Col\Again;

class DI extends Container
{
    public function __construct(array $items = [])
    {
        foreach ($items as $key => $item) {
            $this->offsetSet($key, $item);
        }
    }

    public function container()
    {
        return $this->container;
    }

    public function __call($method, $args)
    {
        return $this->container[$method];
    }

    public function __get($property)
    {
        return $this->container[$property]();
    }

}