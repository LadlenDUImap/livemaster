<?php

namespace app\core;

/**
 * Web содержит вспомогательные функции для взаимодействия с сетью.
 *
 * @package helpers
 */
class Web
{
    /**
     * Проверка является ли запрос AJAX запросом.
     *
     * @return bool
     */
    public static function ifAjax()
    {
        return !empty($_REQUEST['ajax']);
    }

    /**
     * Начинает сессию если ещё не начата.
     */
    public static function startSession()
    {
        if (session_id() == '' || !isset($_SESSION))
        {
            session_start();
        }
    }

    /**
     * Вывести ответ JSON с признаком состояния 'state', данными 'data' и выйти.
     *
     * @param string $state состояние (например, 'success', 'error')
     * @param array $data информация
     */
    public static function sendJsonResponse($state, $data = [])
    {
        header('Content-Type: application/json');
        die(json_encode(['state' => $state, 'data' => $data]));
    }

    /**
     * Вывести html код и завершить работу.
     *
     * @param string $html
     */
    public static function sendHtmlResponse($html)
    {
        header('Content-Type: text/html; charset=UTF-8');
        die($html);
    }
}