<?php

namespace app\helpers;

class ClassHelper
{
    public static function getClassNameNoNamespace(object $object)
    {
        $cache = [];

        $class = get_class($object);

        if (empty($cache[$class])) {
            $cache[$class] = substr(strrchr($class, "\\"), 1);
        }

        return $cache[$class];
    }
}
