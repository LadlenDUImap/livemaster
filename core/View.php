<?php

namespace core;

use core\Container;
use base\Controller;

class View
{
    /** @var string заголовок страницы. */
    public $title;

    /** @var string подключаемые CSS файлы. */
    public $css = '';

    /** @var string подключаемые JS файлы. */
    public $js = '';

    /** @var \core\Container дополнительные (глобальные) значения. */
    public $values;

    public function __construct()
    {
        $this->values = new Container;
    }

    /**
     * Сгенерировать PHP файл.
     *
     * @param string $file Путь к файлу.
     * @param \core\Container $values Контейнер со значениями для файла.
     * @return string Сгенерированный файл.
     */
    //public static function getPhpFileContent($file, Container $values = null)

    /**
     * Сгенерировать PHP файл.
     *
     * @param string $_file_ Путь к файлу.
     * @param Container $values Контейнер с переменными для файла.
     * @return string
     */
    public function render($_file_, Container $values = null)
    {
        if (!$values)
        {
            // Контейнер должен всегда присутствовать.
            $values = new Container;
        }

        ob_start();
        ob_implicit_flush(false);
        require($_file_);
        return ob_get_clean();
    }

    public function renderLayout($file, $content)
    {
        $params = new Container;
        $params->content = $content;
        return $this->render($file, $params);
    }
}
