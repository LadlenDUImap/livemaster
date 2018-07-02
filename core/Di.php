<?php

namespace core;

class Di
{
    public static function set($class, $properties)
    {
        foreach ($properties as $propName => $item) {
            $memberClass = new $item['className'];
            self::configure($memberClass, $item);
            $class->$propName = $memberClass;
        }

        return $class;
    }

    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        return $object;
    }
}
