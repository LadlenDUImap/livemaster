<?php

namespace controllers;

class Controller extends \base\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
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
