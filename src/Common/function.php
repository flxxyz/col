<?php

if (!function_exists('config')) {
    function config($filename, $key = null)
    {
        return Col\Util::config($filename, $key);
    }
}
