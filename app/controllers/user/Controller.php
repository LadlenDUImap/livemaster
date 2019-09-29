<?php

namespace app\controllers\user;

use app\helpers\ClassHelper;
use app\models\db\User;

class Controller extends \app\base\Controller
{
    public function actionUpdate()
    {
        $result = [
            'success' => true,
        ];

        return json_encode($result);
    }

    public function actionCreate()
    {
        $model = new User;

        $attributes = $_POST[ClassHelper::getClassNameNoNamespace($model)];

        $model->verifyProperties($attributes);
        $model->loadProperties($attributes);


        $result = [
            'success' => true,
        ];

        return json_encode($result);
    }
}
