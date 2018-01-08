<?php

namespace Col;

use Curl\Curl;

class Util
{
    /**
     * 构造通用json
     * @param $data
     * @return string
     */
    static function json($data): string
    {
        return self::response()->json($data);
    }

    /**
     * @return object
     */
    static function response(): object
    {
        return new Response;
    }

    /**
     * 构造xml
     * @param $data
     * @return string
     */
    static function xml_encode(array $data): string
    {
        $xml = "<response>";
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $xml .= "<" . $key . ">" . self::xml_encode($val) . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            }
        }
        $xml .= "</response>";
        return $xml;
    }

    /**
     * 解构xml
     * @param string $data
     * @return array
     */
    static function xml_decode(string $data)
    {
        $xml = simplexml_load_string($data);
        return json_decode(json_encode($xml), true);
    }

    /**
     * 获取配置项
     * @param string $filename
     * @param null $key
     * @return mixed|null
     */
    static function config(string $filename, $key = null)
    {
        if (is_null($key)) {
            return require BASE_DIR . "/config/{$filename}.php";
        }

        $data = require BASE_DIR . "/config/{$filename}.php";

        foreach ($data as $k => $v) {
            if ($key !== $k) continue;

            return $data[$k];
        }

        return null;
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
    static function diffDays($time = null): array
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
     * @return object
     */
    static function getHttp($uri = null, $data = [], $referer = null): object
    {
        if (is_null($uri)) {
            return new class
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
     * @return object
     */
    static function postHttp($uri = null, $data = [], $referer = null): object
    {
        if (is_null($uri)) {
            return new class
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