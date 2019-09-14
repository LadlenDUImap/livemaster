<?php

namespace app\base;

interface IProperties
{
    public function loadProperties(array $properties);

    public static function traverseProperties();
}
