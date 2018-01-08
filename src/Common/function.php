<?php

if (!function_exists('config')) {
    /**
     * @param $filename
     * @param null $key
     * @return mixed|null
     */
    function config($filename, $key = null)
    {
        return Col\Util::config($filename, $key);
    }
}

if (!function_exists('view')) {
    /**
     * @param string $view
     * @param $data
     * @return mixed
     */
    function view(string $view, $data)
    {
        return ([new Col\View, 'put'])($view, $data);
    }
}

if (!function_exists('response')) {
    /**
     * @return object
     */
    function response(): object
    {
        return ([new Col\Util, 'response'])();
    }
}

if (!function_exists('json')) {
    /**
     * @param $data
     * @return string
     */
    function json($data): string
    {
        return ([new Col\Util, 'json'])($data);
    }
}

if (!function_exists('xml_encode')) {
    function xml_encode(array $data)
    {
        return ([new Col\Response, 'xml'])($data);
    }
}

if (!function_exists('xml_decode')) {
    function xml_decode(string $data)
    {
        return ([new Col\Util, 'xml_decode'])($data);
    }
}
