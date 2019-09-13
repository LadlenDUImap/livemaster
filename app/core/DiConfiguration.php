<?php

namespace app\core;

/**
 * Class DiConfiguration
 *
 * Внедрение зависимостей по конфигурационным значениям.
 *
 * @package app\core
 */
class DiConfiguration
{
    public static function set($class, $properties)
    {
        foreach ($properties as $propName => $item) {
            $memberClass = new $item['class'];
            unset($item['class']);
            self::configure($memberClass, $item);
            $class->$propName = $memberClass;
        }

        return $class;
    }

    public static function configure(\app\base\Component $object, $properties)
    {
        foreach ($properties as $name => $value) {
            if (empty($value['class'])) {
                $object->$name = $value;
            } else {
                $value = self::set($object, [$name => $value]);
            }
        }

        $object->init();

        return $object;
    }
}
