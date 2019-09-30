<?php

namespace app\core\db;

use app\base\IDatabase;
use app\core\Lm;

class Pdo extends \app\base\Component implements IDatabase
{
    /** @var  string */
    public $dsn;

    /** @var  string */
    public $username;

    /** @var  string */
    public $password;


    /** @var  \PDO */
    protected $DBH;


    public function init()
    {
        if (!$this->DBH = new \PDO($this->dsn, $this->username, $this->password)) {
            throw new \Exception('Не удалось подключиться к БД');
        }
        $this->DBH->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    protected function makeWhere($condition)
    {
        $resultElements = [];

        foreach ($condition as $name => $value) {
            $resultElements[] = "`$name` = :$name";
        }

        return implode(', ', $resultElements);
    }

    public function select(string $tableName, array $condition = [], array $toSelect = ['*']): ?array
    {
        $result = false;

        try {
            $toSelect = array_unique($toSelect);

            array_map(function ($vl) {
                $vl = trim($vl);
                return ($vl == '*') ? $vl : "`$vl`";
            }, $toSelect);

            $query = 'SELECT ' . implode(', ', $toSelect) . ' FROM `' . $tableName . '`';
            if ($whereString = $this->makeWhere($condition)) {
                $query .= ' WHERE ' . $whereString;
            }

            if ($STH = $this->DBH->prepare($query)) {
                $STH->setFetchMode(\PDO::FETCH_ASSOC);
                if ($STH->execute($condition)) {
                    $result = [];
                    while ($row = $STH->fetch()) {
                        $result[] = $row;
                    }
                } else {
                    throw new \Exception('Не удалось выполнить запрос PDO. QUERY: ' . $query . '; CONDITION: ' . print_r($condition));
                }
            } else {
                throw new \Exception('Ошибка подготовки запроса PDO. QUERY: ' . $query . '; CONDITION: ' . print_r($condition));
            }
        } catch (\Exception $e) {
            Lm::inst()->log->set($e);
        }

        return $result;
    }

    public function insert(string $tableName, array $values): bool
    {
        $result = false;

        try {
            $query = 'INSERT INTO `' . $tableName . '`'
                . ' SET ';

            if ($STH = $this->DBH->prepare($query)) {
                $STH->setFetchMode(\PDO::FETCH_ASSOC);
                if ($STH->execute($condition)) {
                    $result = [];
                    while ($row = $STH->fetch()) {
                        $result[] = $row;
                    }
                } else {
                    throw new \Exception('Не удалось выполнить запрос PDO. QUERY: ' . $query . '; CONDITION: ' . print_r($condition));
                }
            } else {
                throw new \Exception('Ошибка подготовки запроса PDO. QUERY: ' . $query . '; CONDITION: ' . print_r($condition));
            }
        } catch (\Exception $e) {
            Lm::inst()->log->set($e);
        }

        return $result;
    }

    public function update(string $tableName, array $values, array $condition): bool
    {
        return true;
    }

    /*public function query($query, $params)
    {
        $STH = $this->DBH->prepare($query);
        $STH->execute($params);
    }*/
}
