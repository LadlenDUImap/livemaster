<?php

namespace app\controllers\user;

use app\core\Web;
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
        $state = 'error';
        $data = [];

        $model = new User;

        $attributes = $_POST[ClassHelper::getClassNameNoNamespace($model)];

        $data['corrected-attributes'] = $model->correctPropertyBulk($attributes);
        $compoundAttributes = array_replace_recursive($attributes, $data['corrected-attributes']);

        if (!$errors = $model->validatePropertyBulk($compoundAttributes)) {
            $state = 'success';
            $model->loadProperties($compoundAttributes);
            $model->save();
        } else {
            $data['error-messages'] = $errors;
        }

        Web::sendJsonResponse($state, $data);
    }
}
