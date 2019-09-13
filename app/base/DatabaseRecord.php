<?php

namespace app\base;

use app\core\Lm;

abstract class DatabaseRecord
{
    private $isNew = true;

    public static $tableName;

    public static $idName = 'id';

    public function __construct($id = false)
    {
        if ($id) {
            if (!$this->load([self::$idName => $id])) {
                throw new \Exception('Неправильный ID: ' . $id);
            }
        }
    }

    public function load($condition)
    {
        if ($rows = Lm::inst()->db->select(static::$tableName, $condition)) {
            $this->loadModelWithProperties($rows);
        }
        return $rows;
    }

    public function getIsNew()
    {
        return $this->isNew;
    }

    public function loadModelWithProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
        $this->isNew = false;
        return $this;
    }

    public static function getAll()
    {
        $items = [];

        if ($rows = Lm::inst()->db->select(static::$tableName)) {
            foreach ($rows as $vals) {
                $newItem = new get_called_class();
                $newItem->loadModelWithProperties($vals);
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
