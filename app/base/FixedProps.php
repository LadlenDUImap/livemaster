<?php

namespace app\base;

abstract class FixedProps
{
    public function __get($name)
    {
        if (!isset($this->$name)) {
            throw new \UnexpectedValueException('Свойство "' . $name . '" не принадлежит классу "' . get_class($this) . "'");
        }

        return $this->$name;
    }

    public function __set($name, $value)
    {
        if (!isset($this->$name)) {
            throw new \UnexpectedValueException('Свойство "' . $name . '" не принадлежит классу "' . get_class($this) . "'");
        }

        $this->$name = $value;
    }
}
