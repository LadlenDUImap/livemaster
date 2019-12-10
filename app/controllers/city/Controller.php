<?php

namespace app\controllers\city;

use app\core\html\Form;
use app\core\Lm;
use app\core\Web;
use app\helpers\HtmlHelper;
use app\models\db\City;

class Controller extends \app\base\Controller
{
    public function actionIndex()
    {
        return $this->render('city/index', ['cities' => City::getAll()]);
    }

    public function actionUpdate()
    {
        assert(!empty($_POST['lm_form_id']));

        $state = 'error';
        $data = [];

        $model = new City();
        $model->setId($_POST['lm_form_id']);

        $attributes = Form::extractModelAttributesFromHtmlAttributes($model, $_POST);
        if ($model->loadProperties($attributes) && $model->save()) {
            $state = 'success';
        } else {
            $data['error-messages'] = HtmlHelper::keys2HtmlModelName($model, $model->getErrors());
        }

        $data['corrected-attributes'] = HtmlHelper::keys2HtmlModelName($model, $model->getCorrectedAttributes());

        Lm::$app->web->sendJsonResponse($state, $data);
    }

    public function actionCreate()
    {
        $state = 'error';
        $data = [];

        $model = new City;

        $attributes = Form::extractModelAttributesFromHtmlAttributes($model, $_POST);
        if ($model->loadProperties($attributes) && $model->save()) {
            $state = 'success';
        } else {
            $data['error-messages'] = HtmlHelper::keys2HtmlModelName($model, $model->getErrors());
        }

        $data['corrected-attributes'] = HtmlHelper::keys2HtmlModelName($model, $model->getCorrectedAttributes());

        Lm::$app->web->sendJsonResponse($state, $data);
    }

    public function actionDelete()
    {
        assert(!empty($_POST['lm_form_id']));

        $state = 'error';
        $data = [];

        try {
            $user = new City($_POST['lm_form_id']);
            if ($user->delete()) {
                $state = 'success';
            } else {
                if ($errors = $user->getErrors()) {
                    $data['error-messages'] = $errors;
                } else {
                    throw new \Exception('Внутренняя ошибка.');
                }
            }
        } catch (\Exception $e) {
            $data['error-messages'] = [
                'Не получилось удалить город.',
                $e->getMessage(),
            ];
        }

        Lm::$app->web->sendJsonResponse($state, $data);
    }
}
