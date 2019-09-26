<?php

namespace app\base;

use app\core\Lm;

/**
 * Class DatabaseRecord
 *
 * Отражает строку в БД. Публичные свойства в производном классе соответствуют полям в БД (кроме начинающихся на "_").
 *
 * @package app\base
 */
abstract class DatabaseRecord implements IProperties
{
    private $_isNew = true;

    /** @var string название таблицы, должно быть переопределено в производном классе */
    protected static $_tableName;

    /** @var string имя первичного ключа */
    protected static $_idName = 'id';


    public function __construct($id = false)
    {
        if ($id) {
            if (!$this->load([self::$_idName => $id])) {
                throw new \Exception('Неправильный ID: ' . $id);
            }
        }
    }

    public function getId() {
        throw new \Exception('ID не реализован для записи');
    }

    public function load($condition)
    {
        if ($rows = Lm::inst()->db->select(static::$_tableName, $condition)) {
            $this->loadProperties($rows);
        }
        return $rows;
    }

    public function getIsNew()
    {
        return $this->_isNew;
    }

    public function loadProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            $this->$name = $value;
        }
        $this->_isNew = false;
        return $this;
    }

    public static function getAll()
    {
        $items = [];

        if ($rows = Lm::inst()->db->select(static::$_tableName)) {
            foreach ($rows as $vals) {
                $className = get_called_class();
                $newItem = new $className;
                $newItem->loadProperties($vals);
                $items[] = $newItem;
            }
        }

        return $items;
    }

    public static function traverseProperties($includeId = false)
    {
        $reflect = new \ReflectionClass(get_called_class());
        $props = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($props as $pr) {
            if ($pr->name[0] !== '_') {
                if (!$includeId && $pr->name == self::$_idName) {
                    continue;
                }
                $ret = [
                    'name' => $pr->name,
                    //'value' => self::{$pr->name},
                ];
                yield $ret;
            }
        }
    }

    /*public function actionVerifyData($field)
    {
        $fname = 'verify' . ucfirst($field);
        if (!is_callable([$this, $fname])) {
            throw new \Exception('Нет такого поля: ' . $field);
        }
        return $this->$fname;
    }*/
}
