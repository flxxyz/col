<?php

namespace Col;

use Col\Database\Connection;
use NotORM;

/**
 * Class Model
 * @package     Col
 * @author      Allisea.Feng <https://blog.flxxxyz.com/>
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 * @version     0.0.6
 */
class Model
{
    private $config;

    private $connection;

    private $table;

    private $perfix;

    public function __construct()
    {
        $config = config('database');
        $this->setConfig($config[$config['defalut']]);
        $this->setConnection(Connection::make($config));
        return $this;
    }

    public function handle()
    {
        return $this->getConnection();
    }

    /**
     * @param NotORM $connection
     */
    private function setConnection(NotORM $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return NotORM
     */
    private function getConnection()
    {
        $tablename = $this->getTable();
        return $this->connection->$tablename;
    }

    /**
     * @param array $config
     */
    private function setConfig(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * @return string
     */
    private function getTable(): string
    {
        return $this->getPerfix() . $this->table;
    }

    /**
     * @param string $perfix
     * @return $this
     */
    public function setPerfix(string $perfix)
    {
        $this->perfix = $this->config['perfix'] ?? $perfix;
        return $this;
    }

    /**
     * @return string
     */
    private function getPerfix(): string
    {
        return $this->perfix;
    }
}