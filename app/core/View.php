<?php

namespace app\core;

class View extends \app\base\FixedProps
{
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

    public function addCssCode($css)
    {
        $this->css .= "<style>\n$css\n</style>";
    }

    /**
     * Сгенерировать PHP файл.
     *
     * @param string $_file_ Путь к файлу.
     * @param mixed $values
     * @return string
     */
    public function render($_file_, $values = null)
    {
        if (!$values) {
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
