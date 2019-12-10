<?php

namespace app\core;

use app\base\Controller;

/**
 * Class Application
 *
 * @package app\core
 */
class Application
{
    /** @var  Controller */
    protected $controller;

    /** @var  array */
    protected $config;


    public function __construct(array $config)
    {
        $this->config = $config;
        Lm::$app = $this;
    }

    public function run()
    {
        DiConfiguration::set($this, $this->config['components']);

        try {
            if (!Lm::$app->csrf->validateCsrfToken()) {
                Web::refreshWithMessage('Неверный CSRF токен. Возможно вышла сессия, попробуйте перезагрузить страницу.', 'no-job-show-message');
            }

            (new Router)->run();
        } catch (\Exception $e) {
            if (LM_DEBUG) {
                $displayMsg = Lm::$app->log->set($e, 'error');
            } else {
                $msg = sprintf(_('Ошибка на сервере, код %s. Пожалуйста сообщите администрации.'), $e->getCode());
                $displayMsg = Lm::$app->log->set($msg, 'error');
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

    public function setController(Controller $controller)
    {
        $this->controller = $controller;
    }
}
