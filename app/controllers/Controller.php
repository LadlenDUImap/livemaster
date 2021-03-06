<?php

namespace app\controllers;

use app\models\db\City;
use app\models\db\User;

class Controller extends \app\base\Controller
{
    public function actionIndex()
    {
        return $this->render('index', ['users' => User::getAll(), 'cities' => City::getAll()]);
    }

    public function action404()
    {
        header('HTTP/1.0 404 Not Found');
        return $this->render('404');
    }

    public function actionNoAction()
    {
        header('HTTP/1.0 404 Not Found');
        return $this->render('404');
    }
}
