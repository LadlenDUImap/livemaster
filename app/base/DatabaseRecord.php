<?php

namespace app\base;

use app\core\Lm;

abstract class DatabaseRecord
{
    public $db;

    private $isNew = true;

    public static $tableName;

    public function __construct(IDatabase $db, $id = false)
    {
        $this->db = $db;

        if ($id) {
            $query = 'SELECT * FROM `' . static::$tableName . '` WHERE `id` = :id';
            if ($rows = $db->select($query, ['id' => $id])) {
                /*foreach ($rows as $name => $val) {
                    $this->$name = $val;
                }*/
                self::fillModelWithProperties($this, $rows);
                $this->isNew = false;
            } else {
                throw new \Exception('Неправильный ID: ' . $id);
            }
        }
    }

    public function getIsNew()
    {
        return $this->isNew;
    }

    public static function fillModelWithProperties($model, array $properties)
    {
        foreach ($properties as $name => $value) {
            $model->$name = $value;
        }
        return $model;
    }

    public static function getAll()
    {
        $items = [];

        $query = 'SELECT * FROM `' . static::$tableName . '`';
        if ($rows = Lm::inst()->db->selectQuery($query)) {
            foreach ($rows as $vals) {
                $newItem = new get_called_class();
                self::fillModelWithProperties($newItem, $vals);
                $items[] = $newItem;
            }
        }

        return $items;
    }

    public function actionVerifyData($field)
    {
        $fname = 'verify' . ucfirst($field);
        if (!is_callable([$this, $fname])) {
            throw new \Exception('Нет такого поля: ' . $field);
        }
        return $this->$fname;
    }
}