<?php

namespace app\core;

use app\core\Web;
use app\core\Validator;

/**
 * Class Model
 * @package core
 */
abstract class Model
{
    //TODO: что это делает в Model ???
    /**
     * Если значение не обнаружено, считается что валидация не пройдена.
     *
     * @param $type
     * @param $varName
     * @param array $data
     */
    //protected function validate($type, $varName, $data = [])
    protected function validate($params, $type)
    {
        $type = 'POST';
        $params = [
            'login' => [
                'isEmpty' => false,
                'lengthNotLess' => [50]
            ],
            'password' => [
                'isEmpty' => false,
                'lengthNotLess' => [150]
            ],
            'password_confirm' => [
                'isEmpty' => false,
                'lengthNotLess' => [150],
                'equalStrings' => ['temp123'],
            ],
            'email' => [
                'email' => false,
            ],
            'phone_mobile' => [
                'phone' => false,
            ]/*,
            'birthdate' => [
                'dateFormat' => false,
            ],*/
        ];

        foreach ($params as $name => $validators)
        {
            $value = Web::getWebData($name, $type);
            foreach ($validators as $valid)
            {
                Validator::$valid();
            }

        }

    }
}