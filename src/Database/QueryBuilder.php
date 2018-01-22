<?php

namespace Col\Database;

use PDO;
use PDOException;

/**
 * Class QueryBuilder
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.4
 */

class QueryBuilder
{
    public $table;  // 表名

    private $connection;  // 查询连接

    private $driver;  // 存储驱动

    private $perfix;  // 表前缀

    public function __construct()
    {
        $config = config('database');
        $this->driver = $config['defalut'];
        $this->perfix = $config[$this->driver]['perfix'] ?? '';
        $this->connection = Connection::make($config);
        $this->setTable($this->table);
    }

    /**
     * 设置表名
     * @param $table
     */
    protected function setTable($table)
    {
        $this->table = $this->perfix . $table;
    }

    /**
     * 查询表全部记录
     * @return array
     */
    public function selectAll()
    {
        try {
            $statement = $this->connection->prepare("select * from {$this->table}");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_CLASS);
        }catch(PDOException $e) {
            $error = "find all row fail, ";
            $error .= "line {$e->getLine()}, ";
            $error .= "<strong>{$e->getMessage()}</strong>";
            exit($error);
        }
    }

    /**
     * id查询记录
     * @param $id
     * @return array|mixed
     */
    public function find($id)
    {
        $sql = "select * from {$this->table} where id = {$id}";
        return $this->query($sql);
    }

    /**
     * 查询一条
     * @param null $sql
     * @return array|mixed|null
     */
    public function get($sql = null)
    {
        $sql = $sql ?? null;
        if (is_null($sql)) {
            return null;
        }

        return $this->query($sql);
    }

    /**
     * 查询多条
     * @param null $sql
     * @return array|mixed|null
     */
    public function select($sql = null)
    {
        $sql = $sql ?? null;
        if (is_null($sql)) {
            return null;
        }

        return $this->query($sql, true);
    }

    /**
     * 封装查询
     * @param      $sql
     * @param bool $all
     * @return array|mixed
     */
    private function query($sql, $all = false)
    {
        try {
            $statement = $this->connection->query($sql);
            if ($all) {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }

            return $statement->fetch(PDO::FETCH_ASSOC);
        }catch(PDOException $e) {
            $error = "find row fail, ";
            $error .= "line {$e->getLine()}, ";
            $error .= "<strong>{$e->getMessage()}</strong>";
            exit($error);
        }
    }

    /**
     * 插入记录
     * @param $parameters
     * @return bool
     */
    public function insert($parameters)
    {
        $sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $this->table, implode(', ', array_keys($parameters)), ':' . implode(', :', array_keys($parameters)));
        try {
            $statement = $this->connection->prepare($sql);
            //            extract($parameters, EXTR_SKIP);

            return $statement->execute($parameters);
        }catch(PDOException $e) {
            $error = "insert row fail, ";
            $error .= "line {$e->getLine()}, ";
            $error .= "<strong>{$e->getMessage()}</strong>";
            exit($error);
        }
    }

    /**
     * 返回最后一次插入记录的id
     * @return string
     */
    public function last_id()
    {
        return $this->connection->lastInsertId();
    }
}