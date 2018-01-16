<?php

namespace Col;

use Col\Common\Util;

/**
 * Class Response
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.1
 */
class Response
{
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

        if(!(is_file($common) || is_file($view))) {
            $body = require_once __DIR__ . "/Common/view/__not_file.php";
        }else {
            if(!is_file($common)) {
                $body = require_once "{$view}";
            }else {
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
        header(sprintf('HTTP/%s %s %s', '1.1', $this->statusCode, ''));
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