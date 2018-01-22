<?php

namespace Col\Common;

use Col\Response;
use Curl\Curl;
use Col\Exceptions\FileNotFoundException;

/**
 * Class Util
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.4
 */
class Util
{
    /**
     * @return object
     */
    static function response(): object
    {
        return new Response;
    }

    /**
     * @param $data
     * @return string
     */
    static function xml($data)
    {
        $xml = '';
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                if (count($val) !== 0) {
                    $xml .= "<" . $key . ">" . self::xml($val) . "</" . $key . ">";
                } else {
                    $xml .= "<{$key}>" . "</{$key}>";
                }
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        return $xml;
    }

    /**
     * 获取配置项
     * @param string $filename
     * @param null   $key
     * @return mixed|null
     */
    static function config(string $filename, $key = null)
    {
        if (!is_file(BASE_DIR . "config/{$filename}.php")) {
            return [];
        }

        if (is_null($key)) {
            return require BASE_DIR . "config/{$filename}.php";
        }

        $data = require BASE_DIR . "config/{$filename}.php";

        foreach ($data as $k => $v) {
            if ($key !== $k)
                continue;

            return $data[$k];
        }

        return [];
    }

    /**
     * 获取客户端ip
     * @return string
     */
    static function getClientIp(): string
    {
        foreach ([
                     'HTTP_CLIENT_IP',
                     'HTTP_X_FORWARDED_FOR',
                     'HTTP_X_FORWARDED',
                     'HTTP_X_CLUSTER_CLIENT_IP',
                     'HTTP_FORWARDED_FOR',
                     'HTTP_FORWARDED',
                     'REMOTE_ADDR',
                 ] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    return $ip;
                    // 过滤掉局域网地址
                    /*if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }*/
                }
            }
        }
    }

    /**
     * 获取访客浏览器型号(感谢博主风小墨)
     * @link https://www.jianshu.com/p/8f76aeaf05ee
     * @param string $user_agent
     * @return string
     */
    static function getBrowser($user_agent = ''): string
    {
        if (preg_match('/MSIE/i', $user_agent)) {
            return 'FUCK IE';
        } elseif (preg_match('/Edge/i', $user_agent)) {
            return 'Edge';
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            return 'Firefox';
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/i', $user_agent)) {
            return 'Safari';
        } elseif (preg_match('/Opera/i', $user_agent)) {
            return 'Opera';
        } else {
            return 'Other';
        }
    }

    /**
     * 获取访客操作系统型号(感谢博主风小墨)
     * @link https://www.jianshu.com/p/8f76aeaf05ee
     * @param string $user_agent
     * @return string
     */
    static function getOS($user_agent = '')
    {
        if (preg_match('/win32|windows/i', $user_agent)) {
            return 'Windows';
        } elseif (preg_match('/mac os x|macintosh/i', $user_agent)) {
            return 'Mac';
        } elseif (preg_match('/linux/i', $user_agent)) {
            return 'Linux';
        } elseif (preg_match('/unix/i', $user_agent)) {
            return 'Unix';
        } elseif (preg_match('/bsd/i', $user_agent)) {
            return 'BSD';
        } else {
            return 'Other';
        }
    }

    /**
     * 检测是否移动端访问
     * @param string $user_agent
     * @return bool
     */
    static function hasMobile($user_agent = '')
    {
        $aMobileUA = array(
            '/iphone/i'     => 'iPhone',
            '/ipod/i'       => 'iPod',
            '/ipad/i'       => 'iPad',
            '/android/i'    => 'Android',
            '/blackberry/i' => 'BlackBerry',
            '/webos/i'      => 'Mobile',
        );

        foreach ($aMobileUA as $sMobileKey => $sMobileOS) {
            if (preg_match($sMobileKey, $user_agent)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 返回一天开始，当前，结束的三种时间戳
     * @param null $time
     * @return array
     */
    static function getDayAll($time = null): array
    {
        $time = $time ?? time();
        $day_seconds = 60 * 60 * 24 - 1;  // 考虑在当天内，减去一秒钟
        $start = strtotime(date('Y-m-d 00:00:00', $time));
        $now = strtotime(date('Y-m-d H:i:s', $time));
        $end = $start + $day_seconds;
        /**
         * @return array
         * @key now    当前时间戳
         * @key start  当天开始时间戳
         * @key end    当天结束时间戳
         */
        return ['now' => $now, 'start' => $start, 'end' => $end,];
    }

    /**
     * 发送http get请求
     * @param null  $uri
     * @param array $data
     * @param null  $referer
     * @return object
     */
    static function getHttp($uri = null, $data = [], $referer = null): object
    {
        if (is_null($uri)) {
            return new Closure;
        }
        $curl = new Curl();
        if (!is_null($referer)) {
            $curl->setReferer($referer);
        }
        $curl->get($uri, $data);

        return $curl;
    }

    /**
     * 发送http post请求
     * @param null  $uri
     * @param array $data
     * @param null  $referer
     * @return object
     */
    static function postHttp($uri = null, $data = [], $referer = null): object
    {
        if (is_null($uri)) {
            return new Closure;
        }
        $curl = new Curl();
        if (!is_null($referer)) {
            $curl->setReferer($referer);
        }
        $curl->post($uri, $data);

        return $curl;
    }

    /**
     * 格式化http响应体
     * @param $body
     * @return null
     */
    static function formatBody($body)
    {
        echo '<pre>';
        print_r($body);
        return null;
    }
}
