<?php
/**
 * Function function
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.6
 */

use Col\{
    Session, Model
};
use Col\Common\Util;

if (!function_exists('response')) {
    function response()
    {
        return Util::response();
    }
}

if (!function_exists('config')) {
    /**
     * 返回配置信息
     * @param string $filename
     * @param        $key
     * @return string
     */
    function config(string $filename, $key = null)
    {
        return Util::config($filename, $key);
    }
}

if (!function_exists('view')) {
    /**
     * 输出视图
     * @param string $name
     * @param array  $data
     * @param int    $stateCode
     * @return mixed
     */
    function view(string $name, array $data = [], int $stateCode = 200)
    {
        $res = response();
        $res->setStatusCode($stateCode);
        return $res->view($name, $data);
    }
}

if (!function_exists('json')) {
    /**
     * 构造json(简写)
     * @param $data
     * @return string
     */
    function json(array $data)
    {
        return response()->json($data);
    }
}

if (!function_exists('xml')) {
    /**
     * 构造xml(简写)
     * @param $data
     * @return string
     */
    function xml(array $data)
    {
        return response()->xml($data);
    }
}

if (!function_exists('xml_encode')) {
    /**
     * 构造xml
     * @param $data
     * @return string
     */
    function xml_encode(array $data): string
    {
        $xml = "<item>";
        $xml .= Util::xml($data);
        $xml .= "</item>";
        return $xml;
    }
}

if (!function_exists('xml_decode')) {
    /**
     * 解构xml
     * @param string $data
     * @return array
     */
    function xml_decode(string $data): array
    {
        $xml = simplexml_load_string($data);
        return json_decode(json_encode($xml), true);
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * 获取客户端ip
     * @return string
     */
    function get_client_ip()
    {
        return Util::getClientIp(get_user_agent());
    }
}

if (!function_exists('get_browser')) {
    /**
     * 获取访客浏览器型号
     * @return string
     */
    function get_browser()
    {
        return Util::getBrowser(get_user_agent());
    }
}

if (!function_exists('get_os')) {
    /**
     * 获取访客操作系统型号
     * @return string
     */
    function get_os()
    {
        return Util::getOS(get_user_agent());
    }
}

if (!function_exists('get_user_agent')) {
    /**
     * 返回user_agent
     * @return string
     */
    function get_user_agent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    }
}

if (!function_exists('has_mobile')) {
    /**
     * 检测是否移动端访问
     * @return bool
     */
    function has_mobile()
    {
        return Util::hasMobile(get_user_agent());
    }
}

if (!function_exists('get_day_all')) {
    /**
     * 返回一天开始，当前，结束的三种时间戳
     * @param null $time
     * @return array
     */
    function get_day_all($time = null)
    {
        return Util::diffDays($time);
    }
}

if (!function_exists('get_microtime')) {
    /**
     * 返回毫秒级时间戳
     * @return float
     */
    function get_microtime(): float
    {
        list($time, $ms) = explode(' ', microtime());
        return $time + $ms;
    }
}

if (!function_exists('http_get')) {
    /**
     * 发送http get请求
     * @param null  $uri
     * @param array $data
     * @param null  $referer
     * @return object
     */
    function http_get($uri = null, $data = [], $referer = null)
    {
        return Util::getHttp($uri, $data, $referer);
    }
}

if (!function_exists('http_post')) {
    /**
     * 发送http get请求
     * @param null  $uri
     * @param array $data
     * @param null  $referer
     * @return object
     */
    function http_post($uri = null, $data = [], $referer = null)
    {
        return Util::postHttp($uri, $data, $referer);
    }
}

if (!function_exists('_e')) {
    function _e($var = '')
    {
        if (is_object($var) || is_array($var)) {
            //            echo '<pre>';
            var_dump($var);
            //            echo '</pre>';
        } elseif (is_null($var)) {
            var_dump(null);
        } else {
            echo $var;
        }
    }
}

if (!function_exists('url')) {
    /**
     * 返回指定的本站url
     * @param string $path
     * @return string
     */
    function url($path = '')
    {
        $config = config('config');
        $url = $config['url'] . $path;
        return $url;
    }
}

if (!function_exists('redirect')) {
    /**
     * 重定向url
     * @param string $url
     */
    function redirect($url = '')
    {
        header("Location: {$url}");
    }
}

if (!function_exists('convert')) {
    /**
     * 压缩数值位数
     * @param string $hash
     * @return string
     */
    function convert(string $hash)
    {
        $bit = base_convert(base_convert($hash, 32, 2) . mt_rand(100, 9999) . time(), 10, 2);
        $bit = base_convert($bit, 2, 36);
        return $bit;
    }
}

if (!function_exists('session')) {
    /**
     * session操作
     * @return Session
     */
    function session()
    {
        return new Session;
    }
}

if (!function_exists('DB')) {
    /**
     * @param string $tablename
     * @param string $perfix
     * @return NotORM
     */
    function DB($tablename = '', $perfix = '')
    {
        $model = new Model;
        return $model->setTable($tablename)->setPerfix($perfix)->handle();
    }
}
