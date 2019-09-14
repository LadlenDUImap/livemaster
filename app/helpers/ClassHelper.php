<?php

namespace app\helpers;

class ClassHelper
{
    public static function getClassNameNoNamespace($class)
    {
        return substr(strrchr(get_class($class), "\\"), 1);
    }
}
