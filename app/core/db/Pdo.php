<?php

namespace app\core\db;

use app\base\IDatabase;

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

    protected function makeWhere($where)
    {

    }

    public function select(string $tableName, array $toSelect = ['*'], array $where = []): array
    {
        $result = [];

        $query = 'SELECT ' . implode(', ', $toSelect) . ' FROM `' . $tableName . '`';
        if ($whereString = $this->makeWhere($where)) {
            $query .= ' WHERE ' . $whereString;
        }

        $STH = $this->DBH->prepare($query);
        $STH->setFetchMode(\PDO::FETCH_ASSOC);
        $STH->execute($params);

        while ($row = $STH->fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function query($query, $params)
    {
        $STH = $this->DBH->prepare($query);
        $STH->execute($params);
    }
}
