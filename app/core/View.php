<?php

namespace app\core;

use app\core\Container;
use app\base\Controller;

class View
{
    /** @var string заголовок страницы. */
    public $title;

    /** @var string подключаемые CSS файлы. */
    public $css = '';

    /** @var string подключаемый JS код. */
    public $js = '';

    /** @var \app\core\Container дополнительные (глобальные) значения. */
    public $values;

    public function __construct()
    {
        $this->values = new Container;
    }

    public function addJsCode($code)
    {
        $this->js .= '<script>' . $code . '</script>';
    }

    /**
     * Сгенерировать PHP файл.
     *
     * @param string $file Путь к файлу.
     * @param \app\core\Container $values Контейнер со значениями для файла.
     * @return string Сгенерированный файл.
     */
    //public static function getPhpFileContent($file, Container $values = null)

    /**
     * Сгенерировать PHP файл.
     *
     * @param string $_file_ Путь к файлу.
     * @param mixed $values
     * @return string
     */
    public function render($_file_, $values = null)
    {
        if (!$values)
        {
            $values = new Container;
        }

        $file = APP_DIR . 'views/content/' . $_file_ . '.php';

        ob_start();
        ob_implicit_flush(false);
        require($file);
        return ob_get_clean();
    }

    public function renderLayout($file, $content)
    {
        $params = new Container;
        $params->content = $content;
        return $this->render($file, $params);
    }
}
