<?php

namespace app\models\db;

use app\base\DatabaseRecord;

class User extends DatabaseRecord
{
    public $name;
    public $age;
    public $city_id;

    protected static $_tableName = 'users';

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

    public function verifyName()
    {
        $this->name = trim($this->name);
        $nameLength = mb_strlen($this->name, 'UTF-8');

        if (!$nameLength) {
            throw new \Exception('Имя пользователя должно быть заполнено');
        }
        if ($nameLength > 30) {
            throw new \Exception('Имя пользователя НЕ должно быть больше 30 символов');
        }

        return true;
    }

    public function verifyAge()
    {
        $this->age = trim($this->age);
        if (!preg_match('^\d+$', $this->age)) {
            throw new \Exception('Возраст пользователя должен быть неотрицательным целым числом');
        }
        if ($this->age > 120) {
            throw new \Exception('Дубы и черепахи не могут быть пользователями');
        }
        return true;
    }
}
