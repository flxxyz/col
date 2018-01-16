<?php

namespace Col;

use ArrayAccess;

class Container implements ArrayAccess
{
    private $items = [];
    private $cache = [];

    /**
     * Container constructor.
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        foreach ($items as $k => $v) {
            $this->offsetSet($k, $v);
        }
    }

    /**
     * @param $offset
     * @return bool
     */
    public function cacheExists($offset)
    {
        return isset($this->cache[$offset]);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * @param $offset
     * @param $var
     * @return bool
     */
    public function has($offset, string $var = 'items')
    {
        switch ($var) {
            case 'items':
                return $this->offsetExists($offset);
                break;
            case 'cache':
                return $this->cacheExists($offset);
                break;
            default:
                return null;
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (!$this->has($offset))
            return null;
        if ($this->has($offset, 'cache'))
            return $this->cache[$offset];

        $item = call_user_func($this->items[$offset], $this);
        $this->cache[$offset] = $item;

        return $this->items[$offset];
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if ($this->has($offset))
            unset($this->items[$offset]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->offsetGet($name);
    }

}