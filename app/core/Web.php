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
     * Перенаправление на другую страницу этого же сайта.
     *
     * @param string $path [[путь][параметры][якорь]] после названия хоста (порта)
     */
    public static function redirect($path = '')
    {
        $host = $_SERVER['HTTP_HOST'];
        $path = ltrim($path, '/');
        header("Location: //$host/$path");
        exit;
    }

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
        /*if ($state == 'error')
        {
            header('HTTP/1.1 500 Internal Server Error');
        }*/
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
        header('Content-Type: text/html; charset=' . Config::inst()->globalEncoding);
        die($html);
    }

    /**
     * Вывести plan text и завершить работу.
     *
     * @param string $text
     */
    public static function sendTextResponse($text)
    {
        header('Content-Type: text/plain; charset=' . Config::inst()->globalEncoding);
        die($text);
    }

    /**
     * Возвращает параметры http запроса если они установлены или пустые строки если нет.
     *
     * @param string $param Название параметра.
     * @param array $data Данные (например массив http).
     * @return mixed Значение параметра.
     */
    public static function getRData($param, $data)
    {
        $ret = '';
        if (isset($data[$param]))
        {
            $ret = $data[$param];
        }
        else
        {
            // Работа с параметрами - массивами (напр. elem[key1][key2])

            preg_match_all("/^([a-zA-Z0-9_]*?)|\[(.+)\]/U", $param, $matches);
            if ($matches[1][0])
            {
                $ret = $data[$matches[1][0]];

                $matchCount = count($matches[2]);
                if ($matchCount > 1)
                {
                    for ($i = 1; $i < $matchCount; ++$i)
                    {
                        if ($matches[2][$i])
                        {
                            $ret = $ret[$matches[2][$i]];
                        }
                        else
                        {
                            break;
                        }
                    }
                }
            }

        }

        return $ret;
    }

    /**
     * Возвращает параметр переменной $_POST.
     *
     * @param string $param Название параметра.
     * @return mixed Значение параметра.
     */
    public static function getPostData($param)
    {
        return self::getRData($param, isset($_POST) ? $_POST : []);
    }

    /**
     * Возвращает параметр переменной $_GET.
     *
     * @param string $param Название параметра.
     * @return mixed Значение параметра.
     */
    public static function getGetData($param)
    {
        return self::getRData($param, isset($_GET) ? $_GET : []);
    }

    /**
     * Возвращает информацию о загруженном файле.
     *
     * @param string $name Название элемента.
     * @return array|bool Информация о файле (см. параметры $_FILES) или false в случае если таковой не существует.
     */
    public static function getUploadedFileInfo($name)
    {
        $ret = false;
        if (!empty($_FILES[$name]))
        {
            // Проверка на корректную загрузку.
            if (empty($_FILES[$name]['tmp_name']) || is_uploaded_file($_FILES[$name]['tmp_name']))
            {
                $ret = $_FILES[$name];
            }
        }
        return $ret;
    }

    /**
     * Перенаправление по POST заданному параметру формы (HtmlForm::REDIRECT_URL_NAME)
     * или на домашнюю страницу если параметр не задан.
     */
    public static function redirectByParam()
    {
        if (!empty($_POST[HtmlForm::REDIRECT_URL_NAME]))
        {
            header('Location: //' . $_POST[HtmlForm::REDIRECT_URL_NAME]);
            exit;
        }
        self::redirect();
    }
}