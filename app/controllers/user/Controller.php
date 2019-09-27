<?php

namespace app\controllers\user;

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
}
