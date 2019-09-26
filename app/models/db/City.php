<?php

namespace app\models\db;

use app\base\DatabaseRecord;

class City extends DatabaseRecord
{
    public $id;
    public $name;

    protected static $_tableName = 'cities';

    /*public function getId()
    {
        return $this->id;
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
}
