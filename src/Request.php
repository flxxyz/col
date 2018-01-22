<?php

namespace Col;

use Col\Common\Util;

/**
 * Class Request
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.4
 */
class Request
{
    private static $instance;
    private $server;

    public function __construct()
    {
        $this->server = $_SERVER;

        $uri = parse_url($this->server['REQUEST_URI'], PHP_URL_PATH);
        $script = $_SERVER['SCRIPT_NAME'];
        $parent = dirname($script);

        if (stripos($uri, $script) !== false) {
            $this->path = substr($uri, strlen($script));
        } elseif (stripos($uri, $parent) !== false) {
            $this->path = substr($uri, strlen($parent));
        } else {
            $this->path = $uri;
        }

        $this->path = preg_replace('/\/+/', '/', '/' . trim(urldecode($this->path), '/') . '/');
        $this->hostname = str_replace('/:(.*)$/', "", $_SERVER['HTTP_HOST']);
        $this->servername = empty($_SERVER['SERVER_NAME']) ? $this->hostname : $_SERVER['SERVER_NAME'];
        $this->secure = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on');
        $this->port = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : null;
        $this->protocol = $this->secure ? 'https' : 'http';
        $this->url = strtolower($this->protocol . '://' . $this->servername) . '/';
        $this->curl = rtrim($this->url, '/') . $this->path;
        $this->extension = pathinfo($this->path, PATHINFO_EXTENSION);
        $this->headers = call_user_func(function () {
            $t = [];
            foreach ($_SERVER as $k => $v) {
                if (stripos($k, 'http_') !== false) {
                    $t[strtolower(substr($k, 5))] = $v;
                }
            }
            return $t;
        });
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->query = $_GET;
        $this->args = [];
        foreach ($this->query as $k => $v) {
            $this->query[$k] = preg_replace('/\/+/', '/', str_replace(['..', './'], ['', '/'], $v));
        }

        if (isset($this->headers['content_type']) && $this->headers['content_type'] == 'application/x-www-form-urlencoded') {
            parse_str(file_get_contents("php://input"), $input);
        } else {
            $input = json_decode(file_get_contents("php://input"), true);
        }

        $this->body = is_array($input) ? $input : [];
        $this->body = array_merge($this->body, $_POST);
        $this->files = isset($_FILES) ? $_FILES : [];
        $this->cookies = $_COOKIE;
        $x_requested_with = isset($this->headers['x_requested_with']) ? $this->headers['x_requested_with'] : false;
        $this->ajax = $x_requested_with === 'XMLHttpRequest';
        $this->user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $this->ip = Util::getClientIp($this->user_agent);
        $this->os = Util::getOS($this->user_agent);
        $this->hasMobile = Util::hasMobile($this->user_agent);
    }

    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    public function __call($method, $args)
    {
        return isset($this->{$method}) && is_callable($this->{$method}) ? call_user_func_array($this->{$method}, $args) : null;
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value instanceof Closure ? $value->bindTo($this) : $value;
    }


}