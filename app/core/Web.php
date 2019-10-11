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
     * Ничего не производя, вывести сообщение на текущей странице (например про не совпадающий CSRF токен).
     *
     * @param string $message
     * @param string $state
     */
    public static function refreshWithMessage(string $message, string $state)
    {
        if (self::ifAjax()) {
            self::sendJsonResponse($state, ['message' => $message]);
        } else {
            //TODO: реализовать
        }
    }

    /**
     * Перенаправление на другую страницу этого же сайта.
     *
     * @param string $path [[путь][параметры][якорь]] после названия хоста (порта)
     */
    /*public static function redirect($path = '')
    {
        $host = $_SERVER['HTTP_HOST'];
        $path = ltrim($path, '/');
        header("Location: //$host/$path");
        exit;
    }*/

    /**
     * Обновление текущей страницы.
     */
    /*public static function refresh()
    {
        header('Location: //' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        exit;
    }*/

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