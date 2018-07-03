<?php

namespace app\core;

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

    public static function configure(\app\base\Component $object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }

        $object->init();

        return $object;
    }
}
