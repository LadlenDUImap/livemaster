<?php

namespace app\controllers\user;

use app\models\db\User;

class Controller extends \app\base\Controller
{
    public function actionUpdate()
    {
        die('123');
        return $this->render('index', ['users' => User::getAll()]);
    }
}
