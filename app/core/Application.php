<?php

namespace app\core;

use app\base\Controller;
use app\base\IDatabase;

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


    /** @var  IDatabase */
    public $db;

    /** @var  Csrf */
    public $csrf;

    /** @var  Log */
    public $log;

    /** @var  Web */
    public $web;

    /** @var  Url */
    public $url;


    public function __construct(array $config)
    {
        $this->config = $config;
        Lm::$app = $this;
        DiConfiguration::set($this, $this->config['components']);
    }

    public function run()
    {
        try {
            if (!$this->csrf->validateCsrfToken()) {
                $this->web->refreshWithMessage('Неверный CSRF токен. Возможно вышла сессия, попробуйте перезагрузить страницу.', 'no-job-show-message');
            }

            (new Router)->run();
        } catch (\Exception $e) {
            if (LM_DEBUG) {
                $displayMsg = $this->log->set($e, 'error');
            } else {
                $msg = sprintf(_('Ошибка на сервере, код %s. Пожалуйста сообщите администрации.'), $e->getCode());
                $displayMsg = $this->log->set($msg, 'error');
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
