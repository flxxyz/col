<?php

namespace Col\Database;

use PDO;
use PDOException;

class Connection
{
    public static function make($config)
    {
        $driver = $config['defalut'];
        $config = $config[$driver];

        try {
            $dsn = "%s:host=%s;port=%s;dbname=%s;charset=%s;";
            $dsn = sprintf($dsn, $driver, $config['host'], $config['port'], $config['database'], $config['charset']);
            return new PDO($dsn, $config['username'], $config['password'], $config['options']);
        }catch(PDOException $e) {
            $error = "database link fail, ";
            $error .= "<strong>{$e->getMessage()}</strong>";
            $error .= "<br>line: $e->getLine()";
            $error .= "<br>file: $e->getFile()";
            exit($error);
        }
    }
}