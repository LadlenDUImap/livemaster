<?php

namespace app\core;

/**
 * Class Container
 *
 * Применяется при герерации html шаблонов.
 * Хранит переменные, при возвращении строки возвращает html-закодиророванную строку.
 * Если переменная не установлена - возвращает Container::ReturnIfNotExists
 *
 * @package app\core
 */
class Container implements \Iterator, \ArrayAccess, \Countable
{
    /** @var array Хранит переменные. */
    protected $objects = [];

    /** Значение по умолчанию при преобразовании в строку (преобразуется в строку если переменная не установлена). */
    const ReturnIfNotExists = '';


    public function __construct($objects = [])
    {
        $this->objects = $objects;
    }

    public function getObjects()
    {
        return $this->objects;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->objects)) {
            return \app\core\html\Safe::htmlEncode($this->objects[$name]);
        }
        // Пустой контейнер - необходим чтобы не было ошибки когда объект ссылается на другой объект/значение.
        return new Container;
    }

    /**
     * Установка значения.
     * При присваивании массивов они преобразуются в объекты текущего класса (Container или производный).
     *
     * @param string $name
     * @param mixed $val
     */
    public function __set($name, $val)
    {
        if (is_array($val)) {
            $this->objects[$name] = new static;
            foreach ($val as $k => $v) {
                $this->objects[$name]->{$k} = new static;
                $this->objects[$name]->{$k} = $v;
            }
        } else {
            $this->objects[$name] = $val;
        }
    }

    public function raw($name)
    {
        if (array_key_exists($name, $this->objects)) {
            return $this->objects[$name];
        }
        // Пустой контейнер - необходим чтобы не было ошибки когда объект ссылается на другой объект/значение.
        return new Container;
    }

    /**
     * На конце цепочки объектов выводим пустую строку если получилось так что объект не задан.
     *
     * @return string
     */
    public function __toString()
    {
        return static::ReturnIfNotExists;
    }

    /**
     *
     * Переопределение функций для реализации \Iterator.
     *
     */

    public function current()
    {
        return current($this->objects);
    }

    public function key()
    {
        return key($this->objects);
    }

    public function next()
    {
        return next($this->objects);
    }

    public function rewind()
    {
        reset($this->objects);
    }

    public function valid()
    {
        $key = key($this->objects);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

    /**
     *
     * Переопределение функций для реализации \ArrayAccess.
     *
     */

    public function offsetExists($offset)
    {
        return isset($this->objects[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->objects[$offset]) ? $this->objects[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->objects[] = $value;
        } else {
            $this->objects[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->objects[$offset]);
    }

    public function count()
    {
        return count($this->objects);
    }
}
