<?php

namespace Col\Database;

use PDO;
use PDOException;
use NotORM;
use NotORM_Structure_Convention;

/**
 * Class Connection
 * @package     Col\Database
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.8
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
            $pdo->exec("set names {$config['charset']}");
            $structure = new NotORM_Structure_Convention(
                'id',
                '%s_id',
                '%s',
                $config['perfix']);
            return new Notorm($pdo, $structure);
        }catch(PDOException $e) {
            $error = "database link fail, ";
            $error .= "<strong>{$e->getMessage()}</strong>";
            $error .= "<br>line: $e->getLine()";
            $error .= "<br>file: $e->getFile()";
            exit($error);
        }
    }
}