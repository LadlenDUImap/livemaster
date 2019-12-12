<?php

namespace app\core;

/**
 * Web содержит вспомогательные функции для взаимодействия с сетью.
 *
 * @package helpers
 */
class Web extends \app\base\Component
{
    /**
     * Проверка является ли запрос AJAX запросом.
     *
     * @return bool
     */
    public function ifAjax()
    {
        return !empty($_REQUEST['ajax']);
    }

    /**
     * Ничего не производя, вывести сообщение на текущей странице (например про не совпадающий CSRF токен).
     *
     * @param string $message
     * @param string $state
     */
    public function refreshWithMessage(string $message, string $state)
    {
        if (self::ifAjax()) {
            self::sendJsonResponse($state, ['message' => $message]);
        } else {
            //TODO: реализовать
        }
    }

    /**
     * Начинает сессию если ещё не начата.
     */
    public function startSession()
    {
        if (session_id() == '' || !isset($_SESSION)) {
            session_start();
        }
    }

    /**
     * Вывести ответ JSON с признаком состояния 'state', данными 'data' и выйти.
     *
     * @param string $state состояние (например, 'success', 'error')
     * @param array $data информация
     */
    public function sendJsonResponse($state, $data = [])
    {
        $this->sendHeader('Content-Type: application/json');
        echo json_encode(['state' => $state, 'data' => $data]);
        $this->callExit();
    }

    /**
     * Вывести html код и завершить работу.
     *
     * @param string $html
     */
    public function sendHtmlResponse($html)
    {
        $this->sendHeader('Content-Type: text/html; charset=UTF-8');
        echo $html;
        $this->callExit();
    }

    public function sendHeader($content)
    {
        header($content);
    }

    public function callExit()
    {
        exit;
    }
}