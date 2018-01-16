<?php

namespace Col;

include_once 'Common/function.php';

class Core
{
    private static $instance;
    public $container;

    public function __construct()
    {
        $this->autoload();
    }

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }

    public function autoload()
    {
        spl_autoload_register(function ($className) {
            $namespace = strtolower(str_replace("\\", DS, __NAMESPACE__));
            $className = str_replace("\\", DS, $className);
            $classNameOnly = basename($className);
            $className = strtolower(substr($className, 0, -strlen($classNameOnly))) . lcfirst($classNameOnly);

            if (is_file($class = BASE_PATH . (empty($namespace) ? "" : $namespace . "/") . "{$className}.php")) {
                return include_once "{$class}";
            } elseif (is_file($class = BASE_PATH . "{$className}.php")) {
                return include_once "{$class}";
            }
        });
    }

    public function __call($method, $args)
    {
        return isset($this->{$method}) && is_callable($this->{$method}) ? call_user_func_array($this->{$method}, $args) : null;
    }

    public function __set($k, $v)
    {
        $this->{$k} = $v instanceof Closure ? $v->bindTo($this) : $v;
    }
}