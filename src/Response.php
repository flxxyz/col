<?php

namespace Col;

use Col\Common\Util;

/**
 * Class Response
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.4
 */
class Response
{
    const HTTP = [
        100 => "HTTP/1.1 100 Continue",
        101 => "HTTP/1.1 101 Switching Protocols",
        200 => "HTTP/1.1 200 OK",
        201 => "HTTP/1.1 201 Created",
        202 => "HTTP/1.1 202 Accepted",
        203 => "HTTP/1.1 203 Non-Authoritative Information",
        204 => "HTTP/1.1 204 No Content",
        205 => "HTTP/1.1 205 Reset Content",
        206 => "HTTP/1.1 206 Partial Content",
        300 => "HTTP/1.1 300 Multiple Choices",
        301 => "HTTP/1.1 301 Moved Permanently",
        302 => "HTTP/1.1 302 Found",
        303 => "HTTP/1.1 303 See Other",
        304 => "HTTP/1.1 304 Not Modified",
        305 => "HTTP/1.1 305 Use Proxy",
        307 => "HTTP/1.1 307 Temporary Redirect",
        400 => "HTTP/1.1 400 Bad Request",
        401 => "HTTP/1.1 401 Unauthorized",
        402 => "HTTP/1.1 402 Payment Required",
        403 => "HTTP/1.1 403 Forbidden",
        404 => "HTTP/1.1 404 Not Found",
        405 => "HTTP/1.1 405 Method Not Allowed",
        406 => "HTTP/1.1 406 Not Acceptable",
        407 => "HTTP/1.1 407 Proxy Authentication Required",
        408 => "HTTP/1.1 408 Request Time-out",
        409 => "HTTP/1.1 409 Conflict",
        410 => "HTTP/1.1 410 Gone",
        411 => "HTTP/1.1 411 Length Required",
        412 => "HTTP/1.1 412 Precondition Failed",
        413 => "HTTP/1.1 413 Request Entity Too Large",
        414 => "HTTP/1.1 414 Request-URI Too Large",
        415 => "HTTP/1.1 415 Unsupported Media Type",
        416 => "HTTP/1.1 416 Requested range not satisfiable",
        417 => "HTTP/1.1 417 Expectation Failed",
        500 => "HTTP/1.1 500 Internal Server Error",
        501 => "HTTP/1.1 501 Not Implemented",
        502 => "HTTP/1.1 502 Bad Gateway",
        503 => "HTTP/1.1 503 Service Unavailable",
        504 => "HTTP/1.1 504 Gateway Time-out",
    ];
    private $body;
    private $statusCode = 200;
    private $headers = [];
    private $type;

    /**
     * 输出视图
     * @param string $name
     * @param array  $data
     */
    public function view(string $name, array $data = [])
    {
        extract($data, EXTR_PREFIX_INVALID, 'view_');  // 非法或数字变量, 添加前缀

        $common = __DIR__ . "/Common/view/{$name}.php";
        $view = APP_DIR . "view/{$name}.view.php";

        if (!(is_file($common) || is_file($view))) {
            $body = require_once __DIR__ . "/Common/view/__not_file.php";
        } else {
            if (!is_file($common)) {
                $body = require_once "{$view}";
            } else {
                $body = require_once "{$common}";
            }
        }

        $this->setBody($body);
    }

    /**
     * 输出json
     * @param array $data
     */
    public function json(array $data)
    {
        $this->type = 'json';
        $this->setBody(json_encode($data, JSON_UNESCAPED_UNICODE));
        $this->setHeader('Content-Type', 'application/json');
    }

    /**
     * 输出xml
     * @param array $data
     */
    public function xml(array $data)
    {
        $this->type = 'xml';
        $this->setBody(xml_encode($data));
        $this->setHeader('Content-Type', 'application/xml');
    }

    /**
     * 普通文本
     * @param $data
     */
    public function text($data)
    {
        $this->type = 'text';
        $this->setBody($data);
    }

    /**
     * 设置http响应体
     * @param $body
     * @return mixed
     */
    public function setBody($body)
    {
        if (is_object($body) || is_array($body)) {
            $body = Util::formatBody($body);
        }

        $this->body = $body;
    }

    /**
     * 输出http响应体
     * @return mixed
     */
    public function getBody()
    {
        $this->withHeaders();
        return $this->body;
    }

    /**
     * 设置http状态码
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * 获取http状态码
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * 设置http响应项
     * @param $name
     * @param $value
     */
    public function setHeader($name, $value)
    {
        $this->headers[] = [$name, $value];
    }

    /**
     * 获取http响应头数组
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * 设置http响应头
     */
    public function withHeaders()
    {
        header(self::HTTP[$this->statusCode]);
        foreach ($this->getHeaders() as $header) {
            header($header[0] . ': ' . $header[1]);
        }
    }

    public function __destruct()
    {
        switch ($this->type) {
            case 'json':
            case 'xml':
            case 'text':
                echo $this->getBody();
                break;
            default:
                return $this->getBody();
        }
    }
}