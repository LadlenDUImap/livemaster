<?php

namespace app\core;

use app\helpers\Helper;
use app\base\Singleton;

/**
 * Class Lm
 *
 * Главный класс приложения.
 *
 * @package app\core
 */
class Lm extends Singleton
{
    protected $config;

    protected $controller;


    public function run($config)
    {
        $this->config = $config;

        DiConfiguration::set($this, $config['components']);

        try {
            /*if (!Csrf::inst()->validateCsrfToken()) {
                //TODO: похоже неправильное поведение (см. validateCsrfToken())
                Web::redirect('?wrong_csrf_token=1');
            }*/

            (new Router)->run();
        } catch (\Exception $e) {
            if (LM_DEBUG) {
                $displayMsg = Lm::inst()->log->set($e);
            } else {
                $msg = sprintf(_('Ошибка на сервере, код %s. Пожалуйста сообщите администрации.'), $e->getCode());
                $displayMsg = Lm::inst()->log->set($msg);
            }
            echo $displayMsg;
        }
    }

    public function config()
    {
        return $this->config;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * Запись логов.
     *
     * @param string|\Exception $message Сообщение или объект исключения.
     * @return string Текст, подготовленный для сохранения в лог.
     */
    /*public function log($message)
    {
        $errorStr = '';

        if (is_string($message)) {
            $errorStr .= $message;
        } elseif ($message instanceof \Exception) {
            $errorStr .= sprintf(
                "Произошла ошибка.\nCode: %s.\nMessage: %s.\nFile: %s.\nLine: %s.\nTrace: %s\n",
                $message->getCode(),
                $message->getMessage(),
                $message->getFile(),
                $message->getLine(),
                $message->getTraceAsString()
            );
        } else {
            $errorStr .= 'Неизвестная ошибка в функции логирования.';
        }

        $msg = date(DATE_RFC822) . ":\n" . $errorStr . "\n-------------------------------------------\n\n";
        error_log($msg);

        return $errorStr;
    }*/
}
