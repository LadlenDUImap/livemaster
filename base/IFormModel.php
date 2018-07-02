<?php

namespace base;

/**
 * IFormModel интерфейс для моделей, обслуживающих визуальные формы.
 *
 * @package base
 */
interface IFormModel
{
    /**
     * Возвращает названия полей.
     *
     * @return array
     */
    public static function attributeLabels();

}