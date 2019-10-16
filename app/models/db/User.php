<?php

namespace app\models\db;

use app\base\DatabaseRecord;
use app\core\Lm;

class User extends DatabaseRecord
{
    protected static $_tableName = 'users';

    protected $_attributes = ['id', 'name', 'age', 'city_id'];


    /*public function setProp($name, $value)
    {
        if ($name === 'city_id' && empty($value)) {
            $value = null;
        }

        parent::setProp($name, $value);
    }*/

    protected function prepareProperties(array $properties): array
    {
        if (isset($properties['city_id']) && empty($properties['city_id'])) {
            $properties['city_id'] = null;
        }

        return $properties;
    }

    public function correctProperty(string $propName, $value)
    {
        $valueMod = is_string($value) ? trim($value) : $value;

        if ($propName == 'name') {
            $valueMod = preg_replace('/\s+/', ' ', $valueMod);
        } elseif ($propName == 'age') {
            if (strlen($valueMod)) {
                $valueMod = ltrim($valueMod, '0');
                if (!strlen($valueMod)) {
                    $valueMod = 0;
                }
            }
        }/* elseif ($propName == 'city_id') {
            if (empty($value)) {
                $value = null;
            }
        }*/

        return ($valueMod === $value) ? false : $valueMod;
    }

    public function validateProperty(string $propName, $value)
    {
        $errMsg = false;

        switch ($propName) {
            case 'name': {
                $errMsg = $this->validateName($value);
                break;
            }
            case 'age': {
                $errMsg = $this->validateAge($value);
                break;
            }
            case 'city_id': {
                // здесь можно проверить есть ли такой город,
                // но в этом мало практической необходимости
                break;
            }
            default: {
                break;
            }
        }

        return $errMsg;
    }

    protected function validateName(string $value)
    {
        $nameLength = mb_strlen($value, 'UTF-8');

        if (!$nameLength) {
            return 'Имя пользователя должно быть заполнено.';
        }
        if ($nameLength > 30) {
            return 'Имя пользователя НЕ должно быть больше 30 символов.';
        }

        return false;
    }

    protected function validateAge(string $value)
    {
        if (!strlen($value)) {
            return 'Возраст пользователя должен быть заполнен.';
        }
        if (!preg_match('/^\d+$/', $value)) {
            return 'Возраст пользователя должен состоять только из цифр.';
        }
        if ((int)$value > 200) {
            return 'Дубы и черепахи не могут быть пользователями (допускается 0 - 200 лет).';
        }

        return false;
    }

    public function validatePropertyBulk(array $properties)
    {
        if (!$errorMessages = parent::validatePropertyBulk($properties)) {
            if ($rows = Lm::inst()->db->select(static::$_tableName, $properties)) {
                $errorMessages[] = 'Такой пользователь уже есть.';
            }
        }

        return $errorMessages;
    }
}
