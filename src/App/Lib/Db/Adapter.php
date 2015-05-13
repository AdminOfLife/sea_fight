<?php

namespace App\Lib\Db;

use InvalidArgumentException;
use PDO;
use PDOStatement;
use RuntimeException;

/**
 *
 * @package App
 * @author Dmitriy Rudenskiy <dmitriy.rudenskiy@gmail.com>
 * @version 1.0.0
 */
class Adapter
{
    /**
     * Database connection
     *
     * @var object|resource|null
     */
    protected static $_connection = null;

    public static function init($config)
    {
        if (empty($config ['database'])) {
            throw new InvalidArgumentException('Config empty');
        }

        $params = $config ['database'];

        if (empty($params['host'])) {
            throw new InvalidArgumentException('Not set "host"');
        }

        if (empty($params['db_name'])) {
            throw new InvalidArgumentException('Not set "db name"');
        }

        if (empty($params['username'])) {
            throw new InvalidArgumentException('Not set "username"');
        }

        if (!isset($params['password'])) {
            throw new InvalidArgumentException('Not set "password"');
        }

        if (!isset($params['charset'])) {
            $params['charset'] = 'UTF8';
        }

        self::setConnect(
            $params['host'],
            $params['db_name'],
            $params['username'],
            $params['password'],
            $params['charset']
        );
    }

    protected static function setConnect($host, $dbName, $username, $password, $charset)
    {
        self::$_connection = new PDO(
            sprintf("mysql:host=%s;dbname=%s", $host, $dbName),
            $username,
            $password
        );

        self::$_connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        if (!empty($charset)) {
            self::$_connection->exec('set names ' . $charset);
        }
    }

    /**
     * Constructor.
     *
     * Check var connect an instance of \PDO.
     *
     * @throws \RuntimeException
     */
    public function __construct()
    {
        if (!self::$_connection instanceof PDO) {
            throw new RuntimeException('Not connect to db');
        }
    }

    /**
     * Fetches the first row of the SQL result.
     *
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function fetchRow($sql, $params = null)
    {
        $stmt = $this->_getStatment($sql, $params);

        if ($stmt instanceof PDOStatement) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function insert($sql, $params = null)
    {
        $this->_getStatment($sql, $params);

        return self::$_connection->lastInsertId();
    }

    /**
     * Prepares and executes an SQL statement with bound data.
     *
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    protected function _getStatment($sql, $params = null)
    {
        /* @var PDOStatement $stmt */
        $stmt = self::$_connection->prepare($sql);

        if (is_array($params) && !empty($params)) {
            foreach ($params as $key => $value) {
                $stmt->bindParam(':' . $key, $params[$key]);
            }
        }

        $stmt->execute();
        return $stmt;
    }
}
