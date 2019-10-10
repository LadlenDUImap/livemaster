<?php

namespace app\controllers\user;

use app\core\html\Form;
use app\core\Lm;
use app\core\Web;
use app\helpers\HtmlHelper;
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

        $attributes = Form::extractModelAttributesFromHtmlAttributes($model, $_POST);
        if ($model->loadProperties($attributes) && $model->save()) {
            $state = 'success';
        } else {
            $data['error-messages'] = HtmlHelper::keys2HtmlModelName($model, $model->getErrors());
        }

        $data['corrected-attributes'] = HtmlHelper::keys2HtmlModelName($model, $model->getCorrectedAttributes());

        Web::sendJsonResponse($state, $data);
    }

    public function actionDelete()
    {
        $state = 'error';

        $_GET['id'] = 999999999;

        try {
            $user = new User($_GET['id']);
            if ($user->delete()) {
                $state = 'success';
            }
        } catch (\Exception $e) {
            $data['error-messages'] = 'Не получилось удалить пользователя.';
        }

        Web::sendJsonResponse($state);
    }
}
