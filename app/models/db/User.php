<?php

namespace app\models\db;

use app\base\DatabaseRecord;

class User extends DatabaseRecord
{
    public $id;
    public $name;
    public $age;
    public $city_id;

    protected static $_tableName = 'users';

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param mixed $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }

    /**
     * @return mixed
     */
    public function getCityId()
    {
        return $this->city_id;
    }

    /**
     * @param mixed $city_id
     */
    public function setCityId($city_id)
    {
        $this->city_id = $city_id;
    }

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

    public function validateName($value)
    {
        $nameLength = mb_strlen($value, 'UTF-8');

        if (!$nameLength) {
            return 'Имя пользователя должно быть заполнено';
        }
        if ($nameLength > 30) {
            return 'Имя пользователя НЕ должно быть больше 30 символов';
        }

        return false;
    }

    public function validateAge($value)
    {
        if (!preg_match('^\d+$', $value)) {
            return 'Возраст пользователя должен быть неотрицательным целым числом';
        }
        if ((int)$value > 200) {
            return 'Дубы и черепахи не могут быть пользователями';
        }

        return false;
    }
}
