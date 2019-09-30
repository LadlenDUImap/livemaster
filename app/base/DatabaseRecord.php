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
abstract class DatabaseRecord
{
    private $_isNew = true;

    /** @var string название таблицы, должно быть переопределено в производном классе */
    protected static $_tableName;

    /** @var string имя первичного ключа */
    protected static $_idName = 'id';

    /** @var array атрибуты таблицы БД array[название => значение] */
    protected $_attributes = [];


    public function __construct($id = false)
    {
        if ($id) {
            if (!$this->load([self::$_idName => $id])) {
                throw new \Exception('Неправильный ID: ' . $id);
            }
        }
    }

    public function getAttributes()
    {
        return $this->_attributes;
    }

    public function setAttributes($attributes)
    {
        foreach ($attributes as $name => $value) {
            $this->setAttr($name, $value);
        }
    }

    public function getAttr($name)
    {
        if (!key_exists($name, $this->_attributes)) {
            throw new \Exception("Не существует значение `$name`");
        }

        return $this->_attributes[$name];
    }

    public function setAttr($name, $value)
    {
        if (!key_exists($name, $this->_attributes)) {
            throw new \Exception("Не существует значение `$name`");
        }

        $this->_attributes[$name] = $value;
    }


    /**
     * Коррекция значения, например, удаление не нужных пробелов по краям строки.
     *
     * @param string $propName
     * @param mixed $value
     * @return mixed|false скорректированное значение, false если значение не нуждается в коррекции
     */
    public function correctProperty(string $propName, $value)
    {
        return false;
    }

    /**
     * array[<название поля>] string
     *
     * @param array $properties (См. вверху)
     * @return array скорректированные значения (только те что изменились)
     */
    public function correctPropertyBulk(array $properties)
    {
        $correcteProperties = [];

        foreach ($properties as $name => $value) {
            if (($correctedValue = $this->correctProperty($name, $value)) !== false) {
                $correcteProperties[$name] = $correctedValue;
            }
        }

        return $correcteProperties;
    }

    /**
     * Проверка одного значения на соответствие правилам.
     *
     * @param string $propName название
     * @param mixed $value значение
     * @return string|false строка с описанием ошибки или false если значение правильно
     */
    public function validateProperty(string $propName, $value)
    {
        return false;
    }

    /**
     * array[<название поля>] string
     *
     * @param array $properties (См. вверху)
     * @return array список ошибок или пустой массив если ошибок не найдено
     */
    public function validatePropertyBulk(array $properties)
    {
        $errorMessages = [];

        foreach ($properties as $name => $value) {
            if ($errMsg = $this->validateProperty($name, $value)) {
                $errorMessages[$name] = $errMsg;
            }
        }

        return $errorMessages;
    }

    public function getId()
    {
        throw new \Exception('ID не реализован для записи');
    }

    public function load($condition)
    {
        if ($rows = Lm::inst()->db->select(static::$_tableName, $condition)) {
            $this->setAttributes($rows);
            $this->_isNew = false;
        }
        return $rows;
    }

    public function getIsNew()
    {
        return $this->_isNew;
    }

    public static function getAll()
    {
        $items = [];

        if ($rows = Lm::inst()->db->select(static::$_tableName)) {
            foreach ($rows as $vals) {
                $className = get_called_class();
                $newItem = new $className;
                $newItem->setAttributes($vals);
                $newItem->_isNew = false;
                $items[] = $newItem;
            }
        }

        return $items;
    }

    public function save()
    {
        if ($this->_isNew) {
            Lm::inst()->db->insert(static::$_tableName, $this->_attributes);
        } else {
            Lm::inst()->db->update(static::$_tableName, $this->_attributes,
                [self::$_idName => $this->_attributes[self::$_idName]]);
        }
    }
}
