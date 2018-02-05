<?php

namespace Col\Database;

use PDO;
use PDOException;
use NotORM;

/**
 * Class Connection
 * @package     Col\Database
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.6
 */
class Connection
{
    public static function make($config)
    {
        $driver = $config['defalut'];
        $config = $config[$driver];

        try {
            $dsn = "%s:host=%s;port=%s;dbname=%s;charset=%s;";
            $dsn = sprintf($dsn, $driver, $config['host'], $config['port'], $config['database'], $config['charset']);
            $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
            return new Notorm($pdo);
        }catch(PDOException $e) {
            $error = "database link fail, ";
            $error .= "<strong>{$e->getMessage()}</strong>";
            $error .= "<br>line: $e->getLine()";
            $error .= "<br>file: $e->getFile()";
            exit($error);
        }
    }
}