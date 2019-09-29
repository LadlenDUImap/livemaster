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

    /*public function setId($id)
    {
        $this->id = $id;
    }*/

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

    public function validate(string $propName, $value)
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
            default: {
                break;
            }
        }

        return $errMsg;
    }

    public function validateBulk(array $properties)
    {
        $errorMessages = [];

        foreach ($properties as $prop) {
            if ($errMsg = $this->validate($prop['name'], $prop['value'])) {
                $errorMessages[] = $errMsg;
            }
        }

        return $errorMessages;
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
