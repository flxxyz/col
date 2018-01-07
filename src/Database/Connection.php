<?php

namespace Col\Database;

use PDO;
use PDOException;

class Connection
{
    public function make($container)
    {
        $config = $container['config']['database'];

        try {
            return new PDO(
                $config['driver'] . 'host=' . $config['host'] . ';dbname=' . $config['database'],
                $config['username'],
                $config['password'],
                $config['options']);
        } catch (PDOException $e) {
            $error = '<pre>';
            $error .= '<br>message: ' . $e->getMessage();
            $error .= '<br>line: ' . $e->getLine();
            $error .= '<br>file: ' . $e->getFile();
            exit($error);
        }
    }
}