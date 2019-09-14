<?php

namespace app\core\html;

use app\base\IProperties;
use app\core\Lm;

class Form
{
    /** @var  IProperties */
    protected $model;

    /** @var int порядковый идентификатор следующей (или уже создающейся) формы */
    protected static $id = 0;


    public function __construct($model)
    {
        $this->model = $model;
    }

    public function start($id = false)
    {
        $id = $id ?: self::$id;
        return '<form id="lm_form_' . $id . '">' . "\n"
            . '<input type="hidden" id="lm_form_csrf_name_' . $id . '" value="' . Lm::inst()->csrf->getCsrfTokenName() . '" />' . "\n"
            . '<input type="hidden" id="lm_form_csrf_value_' . $id . '" value="' . Lm::inst()->csrf->getCsrfToken() . '" />' . "\n";
    }

    public function end()
    {
        ++self::$id;
        return '</form>';
    }

    public static function getCurrentId()
    {
        return self::$id;
    }

    public function textInput($name = '', $params = [])
    {
        if ($this->model) {
            $nameHtml = ' name="' . get_class($this->model) . '[' . Safe::htmlEncode($name) . ']' . '" ';
        } else {
            $nameHtml = $name ? ' name="' . Safe::htmlEncode($name) . '" ' : '';
        }

        $paramsHtml = '';
        if ($params) {
            foreach ($params as $pName => $pVal) {
                $paramsHtml .= ' ' . $pName . '="' . Safe::htmlEncode($pVal) . '" ';
            }
        }

        $valueHtml = '';
        if ($this->model && $name) {
            $valueHtml = ' value="' . Safe::htmlEncode($this->model->$name) . '" ';
        }

        return '<input type="text"' . $nameHtml . $valueHtml . $paramsHtml . ' />';
    }
}
