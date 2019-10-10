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

    /**
     * Защита от потенциального взлома - подготовка названия таблицы/колонки.
     *
     * @param string $name
     */
    protected function makeNameSafe(string $name)
    {
        return preg_replace('/[^A-Za-z0-9_]+/', '', $name);
    }

    /**
     * Создать цепь шаблонов типа "`a` = :a", соединенных $glue. Для части PDO запроса.
     *
     * @param array $condition
     * @param string $glue
     * @return string
     */
    protected function makeEqualQueryTerm(array $condition, string $glue)
    {
        $resultElements = [];

        foreach ($condition as $name => $value) {
            $name = $this->makeNameSafe($name);
            $resultElements[] = "`$name` = :$name";
        }

        return implode(" $glue ", $resultElements);
    }

    public function select(string $tableName, array $condition = [], array $toSelect = ['*']): ?array
    {
        $result = false;

        $tableName = $this->makeNameSafe($tableName);

        $toSelect = array_unique($toSelect);
        array_map(function ($vl) {
            $vl = $this->makeNameSafe($vl);
            return ($vl === '*') ? $vl : "`$vl`";
        }, $toSelect);

        $query = 'SELECT ' . implode(', ', $toSelect) . ' FROM `' . $tableName . '`';
        if ($whereString = $this->makeEqualQueryTerm($condition, 'AND')) {
            $query .= ' WHERE ' . $whereString;
        }

        try {
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
            Lm::inst()->log->set($e, 'error');
        }

        return $result;
    }

    public function insert(string $tableName, array $values): bool
    {
        $result = false;

        $tableName = $this->makeNameSafe($tableName);

        $query = 'INSERT INTO `' . $tableName . '`'
            . ' SET ' . $this->makeEqualQueryTerm($values, ',');

        if (LM_DEBUG) {
            Lm::inst()->log->set('Вставка SQL: ' . $query);
        }

        //TODO: остановился - переместить вместе с update в отдельную функцию
        try {
            if ($STH = $this->DBH->prepare($query)) {
                //$STH->setFetchMode(\PDO::FETCH_ASSOC);
                if (!$result = $STH->execute($values)) {
                    throw new \Exception('Не удалось выполнить запрос PDO. QUERY: ' . $query . '; VALUES: ' . print_r($values));
                }
            } else {
                throw new \Exception('Ошибка подготовки запроса PDO. QUERY: ' . $query . '; VALUES: ' . print_r($values));
            }
        } catch (\Exception $e) {
            Lm::inst()->log->set($e, 'error');
        }

        return $result;
    }

    public function update(string $tableName, array $values, array $condition): bool
    {
        $result = false;

        $tableName = $this->makeNameSafe($tableName);

        $query = 'UPDATE `' . $tableName . '`'
            . ' SET ' . $this->makeEqualQueryTerm($values, ',');

        if ($condition) {
            $query .= ' WHERE ' . $this->makeEqualQueryTerm($values, ' AND ');
        }

        if (LM_DEBUG) {
            Lm::inst()->log->set('Обновление SQL: ' . $query);
        }

        try {
            if ($STH = $this->DBH->prepare($query)) {
                if (!$result = $STH->execute($values)) {
                    throw new \Exception('Не удалось выполнить запрос PDO. QUERY: ' . $query . '; VALUES: ' . print_r($values));
                }
            } else {
                throw new \Exception('Ошибка подготовки запроса PDO. QUERY: ' . $query . '; VALUES: ' . print_r($values));
            }
        } catch (\Exception $e) {
            Lm::inst()->log->set($e, 'error');
        }

        return $result;
    }

    public function lastInsertId()
    {
        return $this->DBH->lastInsertId();
    }
}
