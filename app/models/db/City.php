<?php

namespace app\models\db;

use app\base\DatabaseRecord;
use app\core\Lm;

class City extends DatabaseRecord
{
    protected static $_tableName = 'cities';

    protected $_attributes = ['id', 'name'];


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
            return 'Название города должно быть заполнено.';
        }
        if ($nameLength > 30) {
            return 'Название города НЕ должно быть больше 30 символов.';
        }
        if (Lm::inst()->db->select(static::$_tableName, ['name' => $value])) {
            return 'Такой город уже есть.';
        }

        return false;
    }

    public function delete()
    {
        // Проверим нет ли связи с другими таблицами
        $this->setErrors(null);

        if (Lm::inst()->db->select(User::tableName(), ['city_id' => $this->getId()])) {
            $this->setErrors(['Этот город используетс как знаение пользовател. Удалите.']);
            return fale;
        }

        return parent::delete();
    }
}
