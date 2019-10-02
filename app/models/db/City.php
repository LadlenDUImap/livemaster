<?php

namespace app\models\db;

use app\base\DatabaseRecord;

class City extends DatabaseRecord
{
    protected static $_tableName = 'cities';

    protected $_attributes = ['id' => null, 'name' => null];


    public function correctProperty(string $propName, $value)
    {
        $valueMod = trim($value);

        if ($propName == 'name') {
            $valueMod = preg_replace('/\s+/', ' ', $valueMod);
        }

        return ($valueMod === $value) ? false : $valueMod;
    }

    public function validateProperty(string $propName, $value)
    {
        $errMsg = false;

        if ($propName == 'name') {
            $errMsg = $this->validateName($value);
        }

        return $errMsg;
    }

    public function validateName(string $value)
    {
        $nameLength = mb_strlen($value, 'UTF-8');

        if (!$nameLength) {
            throw new \Exception('Название города должно быть заполнено.');
        }
        if ($nameLength > 30) {
            throw new \Exception('Название города НЕ должно быть больше 30 символов.');
        }

        return true;
    }
}
