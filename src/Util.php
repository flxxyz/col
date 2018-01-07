<?php

namespace Col;

use Curl\Curl;

class Util
{
    /**
     * 构造通用json返回
     * @param $data
     * @return string
     */
    static function json($data)
    {
        return json_encode($data);
    }

    static function config($filename, $key = null)
    {
        if (is_null($key)) {
            return require APP_DIR . "/config/{$filename}.php";
        }

        $data = require APP_DIR . "/config/{$filename}.php";

        foreach ($data as $k => $v) {
            if($key !== $k) continue;

            return $data[$k];
        }

    }

    /**
     * 获取客户端ip
     * @return string
     */
    static function getClientIp()
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
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
     * 返回一天开始，当前，结束的三种时间戳
     * @param null $time
     * @return array
     */
    static function diffDays($time = null)
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
        return [
            'now' => $now,
            'start' => $start,
            'end' => $end,
        ];
    }

    /**
     * 发送http get请求
     * @param null $uri
     * @param array $data
     * @param null $referer
     * @return Curl|__anonymous@1717
     */
    static function getHttp($uri = null, $data = [], $referer = null)
    {
        if (is_null($uri)) {
            return new Class
            {
            };
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
     * @param null $uri
     * @param array $data
     * @param null $referer
     * @return Curl|__anonymous@2194
     */
    static function postHttp($uri = null, $data = [], $referer = null)
    {
        if (is_null($uri)) {
            return new Class
            {
            };
        }
        $curl = new Curl();
        if (!is_null($referer)) {
            $curl->setReferer($referer);
        }
        $curl->post($uri, $data);
        return $curl;
    }
}