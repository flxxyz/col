<?php

namespace Col;

/**
 * Class Session
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.4
 */

class Session
{
    private $session;

    public function __construct()
    {
        $this->dump();
    }

    public static function make()
    {
        $config = config('session');
        session_cache_expire($config['expire']);
        session_cache_limiter($config['limiter']);
        session_name($config['perfix']);
        session_start();
    }

    /**
     * @param bool $flag
     */
    private function dump(bool $flag = false)
    {
        if ($flag) {
            $_SESSION = $this->session;
        } else {
            $this->session = $_SESSION;
        }
    }

    /**
     * 设置session
     */
    public function set()
    {
        $args = func_get_args();

        $param = $args[0];
        foreach ($param as $k => $v) {
            $this->session[$k] = $v;
        }

        $this->dump(true);
    }

    /**
     * 获取session
     * @param $name
     * @return null
     */
    public function get($name)
    {
        return $this->session[$name] ?? null;
    }

    /**
     * 获取所有session
     * @return mixed
     */
    public function all()
    {
        return $this->session;
    }

    /**
     * 重置session
     * @param bool $flag
     */
    public function reset(bool $flag = false)
    {
        if ($flag) {
            $this->set(['time' => time()]);
            $time = $this->get('time');
            $token = md5($this->id . $time . $this->config['key']);
        } else {
            $time = $this->get('time');
            $token = $this->get('token') ?? md5($this->id . $time . $this->config['key']);
        }

        $this->set(['token' => $token]);
    }
}