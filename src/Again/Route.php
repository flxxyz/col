<?php

namespace Col\Again;

/**
 * Class Route
 *
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @package     Col\Again
 */
class Route
{
    protected $routes = [];
    private $prefix = '';

    private function add(string $uri, callable $handler, $method)
    {
        $slash_found = preg_match('/^\//', $uri);
        if (!$slash_found) {
            $uri = '/' . $uri;
            $uri = $this->isPrefix() ? $uri : $this->prefix . $uri;
            $this->clearPrefix();
        }

        $this->routes[$uri][$method] = $handler;
    }

    public function get(string $uri, callable $handler)
    {
        $this->add($uri, $handler, 'GET');
    }

    public function post(string $uri, callable $handler)
    {
        $this->add($uri, $handler, 'POST');
    }

    public function prefix(string $name)
    {
        $this->prefix = '/' . $name;

        return $this;
    }

    private function isPrefix()
    {
        return $this->prefix === '';
    }

    private function clearPrefix()
    {
        $this->prefix = '';
    }

    public function group($name, callable $cb)
    {
        if (is_array($name)) {
            foreach ($name as $p) {
                $this->group($p, $cb);
            }

            return $this;
        }

        $name = $this->removeDuplSlash($name . '/');

    }

    private function removeDuplSlash($uri)
    {
        return preg_replace('/\/+/', '/', '/' . $uri);
    }

    private function trimSlash($uri)
    {
        return trim($uri, '/');
    }
}

$route = new Route();
$route->get('info', function () {
    return 'GET=user info page';
});

$route->prefix('user')->post('info', function () {
    return 'POST=user info page';
});

$route->group('info', function () {
    $this->post('show', function () {
        return 'POST=info show page';
    });
});

var_dump($route);


