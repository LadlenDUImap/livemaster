<?php

namespace controllers\user\info;

se models\forms\UserInfoForm;
use models\User;
use core\Web;
use core\Validator;
use core\Container;
use helpers\Helper;

class Controller extends \base\Controller
{
    public function actionIndex()
    {
        $uid = isset($_GET['uid']) ? $_GET['uid'] : false;

        if ($uid)
        {

            $model = new User();
            $values = new Container();

            if ($info = $model->getInfo(
                $_GET['uid'],
                [
                    'id',
                    'login',
                    'first_name',
                    'last_name',
                    'email',
                    'phone_mobile',
                    'birthday',
                    'gender',
                    'country_code',
                    'address',
                    'image',
                    'image_thumb'
                ]
            )
            )
            {
                $values->info = $info;
                $values->labels = $model->attributeLabelsColon();
                Helper::setSelectedMenu($this->view->values, 'info');

                return $this->render('user/info/index', $values);
            }
        }

        //Web::redirect('?action=404');
        return $this->render('404');
    }

    public function actionEdit()
    {
        $uid = isset($_GET['uid']) ? $_GET['uid'] : false;

        $model = new UserInfoForm($uid);

        if ($model->validate() && ($id = $model->save()))
        {
            Web::redirect('user/info?uid=' . $id);
        }

        return $this->render('user/info/modify', $model->getHtmlValues());
    }

    /*public function actionNew()
    {
        $model = new UserInfoForm;

        if ($model->validate() && $model->save())
        {
            Web::redirect('user/info');
        }

        return $this->render('user/info/modify', $model->getHtmlValues());
    }*/

    public function actionImage()
    {
        $id = $_GET['uid'];
        $type = empty($_GET['thumb']) ? 'image' : 'image_thumb';

        $image = (new User)->getInfo($id, $type);
        if (!empty($image[$type]))
        {
            $finfo = new \finfo(FILEINFO_MIME);
            if ($mime = $finfo->buffer($image[$type]))
            {
                header('Content-Type: ' . $mime);
                return $image[$type];
            }
        }

        header('HTTP/1.0 404 Not Found');
        return '';
    }

    public function actionNotInDbList()
    {
        $validator = new Validator(new UserInfoForm, ['\\app\\core\\Web', 'getGetData']);
        $check = $validator->notInDbList($_GET['field'], $_GET['table'], $_GET['column']);
        if ($check)
        {
            Web::sendJsonResponse('success');
        }
        else
        {
            Web::sendJsonResponse('error', $validator->getLastError());
        }
    }

    /*public function actionAddUser()
    {
        //$pData = Web::getRData(['login', 'password', 'password_confirm']);

        $saveErrors

        $pData = User::trimFields($_POST);


        Validator::bunchValidation(
            [
                ['requiredField' => null,
                'minimalLength': $pData['login']]
        ]
    );
    }*/

    /* public function actionModify()
     {
         $this->actionNew();
         die('actionModify');
     }*/

}