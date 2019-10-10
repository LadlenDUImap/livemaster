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


    /** @var array названия атрибутов таблицы БД array[название] */
    protected $_attributes = [];


    private $_correctedAttributes = [];

    private $_errors = [];


    public function __construct($id = false)
    {
        if ($id) {
            if (!$this->load([static::$_idName => $id])) {
                throw new \Exception('Неправильный ID: ' . $id);
            }
        }
    }

    public static function getDb(): IDatabase
    {
        return Lm::inst()->db;
    }

    public function __get($name)
    {
        if (!in_array($name, $this->_attributes)) {
            throw new \Exception("Не существует поле `$name`");
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->setProp($name, $value);
    }

    protected function setCorrectedAttributes(?array $correctedAttributes)
    {
        $this->_correctedAttributes = $correctedAttributes ?: [];
    }

    public function getCorrectedAttributes(): array
    {
        return $this->_correctedAttributes;
    }

    protected function setErrors(?array $errors)
    {
        $this->_errors = $errors ?: [];
    }

    public function getErrors(): array
    {
        return $this->_errors;
    }

    //$this->_attributes - это только те атрибуты, которые можно устанавливать
    /*public function getAttributes()
    {
        return $this->_attributes;
    }*/

    /**
     * @return array [<название> => <значение>]
     */
    public function getProperties()
    {
        $properties = [];

        foreach ((new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PUBLIC) as $prop) {
            if (!$prop->isStatic()) {
                $properties[$prop->getName()] = $this->{$prop->getName()};
            }
        }

        return $properties;
    }

    protected function setProperties(array $properties)
    {
        foreach ($properties as $name => $value) {
            $this->setProp($name, $value);
        }
    }

    /**
     * Установка значений атрибутов с коррекцией и проверкой (используется при установке из внешнего источника).
     * Если проверка не проходит, то значения атрибутов не устанавливаются.
     * Устанавливаются скорректированные атрибуты если проверка прошла успешно.
     *
     * @param array $properties
     * @param bool $correct
     * @param bool $validate
     */
    public function loadProperties(array $properties, $correct = true, $validate = true)
    {
        $result = false;

        $this->setErrors(null);

        $correctedProperties = $correct ? $this->correctPropertyBulk($properties) : $properties;
        $this->setCorrectedAttributes($correctedProperties);
        $compoundAttributes = array_replace_recursive($properties, $correctedProperties);

        if ($validate) {
            if (!$errors = $this->validatePropertyBulk($compoundAttributes)) {
                $this->setProperties($compoundAttributes);
                $result = true;
            } else {
                $this->setErrors($errors);
            }
        } else {
            $result = true;
        }

        return $result;
    }

    /*public function getAttr($name)
    {
        if (!key_exists($name, $this->_attributes)) {
            throw new \Exception("Не существует значение `$name`");
        }

        return $this->_attributes[$name];
    }*/

    public function setProp($name, $value)
    {
        if (!in_array($name, $this->_attributes)) {
            throw new \Exception("Не существует поле `$name`");
        }

        $this->$name = $value;

        // Установка идентификатора автоматически обозначает что это уже существующая запись в БД.
        if ($name === static::$_idName) {
            $this->_isNew = false;
        }
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
        //throw new \Exception('ID не реализован для записи');
        return $this->{static::$_idName};
    }

    public function setId($value)
    {
        $this->setProp(static::$_idName, $value);
    }

    public function load($condition)
    {
        if ($rows = static::getDb()->select(static::$_tableName, $condition)) {
            $this->setProperties($rows);
        }
        return $rows;
    }

    public function isNew()
    {
        return $this->_isNew;
    }

    public static function getAll()
    {
        $items = [];

        if ($rows = static::getDb()->select(static::$_tableName)) {
            foreach ($rows as $vals) {
                $className = get_called_class();
                $newItem = new $className;
                $newItem->setProperties($vals);
                $items[] = $newItem;
            }
        }

        return $items;
    }

    public function save()
    {
        $result = false;

        $db = static::getDb();

        if ($this->isNew()) {
            $result = $db->insert(static::$_tableName, $this->getProperties());
            $this->setId($db->lastInsertId());
        } else {
            $result = $db->update(static::$_tableName, $this->getProperties(),
                [static::$_idName => $this->getId()]);
        }

        return $result;
    }
}
