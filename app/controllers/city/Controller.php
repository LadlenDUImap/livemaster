<?php

namespace app\controllers\user;

use app\core\html\Form;
use app\core\Web;
use app\helpers\HtmlHelper;
use app\models\db\City;

class Controller extends \app\base\Controller
{
    public function actionIndex()
    {
        die(33);
        return $this->render('index', ['cities' => City::getAll()]);
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

        Web::sendJsonResponse($state, $data);
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

        Web::sendJsonResponse($state, $data);
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
            }
        } catch (\Exception $e) {
            $data['error-messages'] = [
                'Не получилось удалить город.',
                $e->getMessage(),
            ];
        }

        Web::sendJsonResponse($state, $data);
    }
}
