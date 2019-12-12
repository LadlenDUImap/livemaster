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


    /** @var array установленные атрибуты */
    protected $_setAttributes = [];


    private $_correctedAttributes = [];

    private $_errors = [];


    public function __construct($id = false)
    {
        if ($id) {
            if (!$this->load([static::$_idName => $id])) {
                throw new \Exception('Неправильный ID: ' . $id, 404);
            }
        }
    }

    public function __get($name)
    {
        if (!in_array($name, $this->_attributes)) {
            throw new \Exception("Не существует поле `$name`");
        }

        return $this->_setAttributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->setProp($name, $value);
    }

    public static function getDb(): IDatabase
    {
        return Lm::$app->db;
    }

    public static function tableName()
    {
        return static::$_tableName;
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

    /**
     * @return array [<название> => <значение>]
     */
    public function getProperties()
    {
        return $this->_setAttributes;
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

        $properties = $this->prepareProperties($properties);

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

    /**
     * Начальная подготовка значений к операциям, в БД, к примеру.
     *
     * @param array $properties
     * @return array
     */
    protected function prepareProperties(array $properties): array
    {
        return $properties;
    }

    public function setProp($name, $value)
    {
        if (!in_array($name, $this->_attributes)) {
            throw new \Exception("Не существует поле `$name`");
        }

        $this->_setAttributes[$name] = $value;

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
        $correctedProperties = [];

        foreach ($properties as $name => $value) {
            if (($correctedValue = $this->correctProperty($name, $value)) !== false) {
                $correctedProperties[$name] = $correctedValue;
            }
        }

        return $correctedProperties;
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
    public function validatePropertyBulk(array $properties): array
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

    public function setId($value): void
    {
        $this->setProp(static::$_idName, $value);
    }

    public function load($condition)
    {
        if ($rows = static::getDb()->select(static::tableName(), $condition)) {
            $this->setProperties($rows[0]);
        }
        return $rows;
    }

    public function isNew(): bool
    {
        return $this->_isNew;
    }

    public static function getAll(): array
    {
        $items = [];

        if ($rows = static::getDb()->select(static::tableName())) {
            foreach ($rows as $vals) {
                $className = get_called_class();
                $newItem = new $className;
                $newItem->setProperties($vals);
                $items[] = $newItem;
            }
        }

        return $items;
    }

    public function save(): bool
    {
        $result = false;

        $db = static::getDb();

        if ($this->isNew()) {
            $result = $db->insert(static::tableName(), $this->getProperties());
            $this->setId($db->lastInsertId());
        } else {
            $result = $db->update(static::tableName(), $this->getProperties(),
                [static::$_idName => $this->getId()]);
        }

        return $result;
    }

    public function delete(): bool
    {
        $result = false;

        if (!$this->isNew()) {
            $result = static::getDb()->delete(static::tableName(), [static::$_idName => $this->getId()]);
        }

        return $result;
    }
}
