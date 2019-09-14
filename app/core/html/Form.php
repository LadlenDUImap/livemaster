<?php

namespace app\core\html;

use app\base\IProperties;
use app\core\Lm;
use app\helpers\ClassHelper;

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
        return '<form id="lm_form_' . $id . '">'
            . '<input type="hidden" id="lm_form_csrf_name_' . $id . '" value="' . Lm::inst()->csrf->getCsrfTokenName() . '" />'
            . '<input type="hidden" id="lm_form_csrf_value_' . $id . '" value="' . Lm::inst()->csrf->getCsrfToken() . '" />';
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

    public function partHtmlName($name = '')
    {
        if ($this->model) {
            $nameHtml = ' name="' . ClassHelper::getClassNameNoNamespace($this->model) . '[' . Safe::htmlEncode($name) . ']' . '" ';
        } else {
            $nameHtml = $name ? ' name="' . Safe::htmlEncode($name) . '" ' : '';
        }

        return $nameHtml;
    }

    public function partHtmlParams($params = [])
    {
        $paramsHtml = '';

        if ($params) {
            foreach ($params as $pName => $pVal) {
                $paramsHtml .= ' ' . $pName . '="' . Safe::htmlEncode($pVal) . '" ';
            }
        }

        return $paramsHtml;
    }

    public function textInput($name = '', $params = [])
    {
        $nameHtml = $this->partHtmlName($name);
        $paramsHtml = $this->partHtmlParams($params);

        $valueHtml = '';
        if ($this->model && $name) {
            $valueHtml = ' value="' . Safe::htmlEncode($this->model->$name) . '" ';
        }

        return '<input type="text"' . $nameHtml . $valueHtml . $paramsHtml . ' />';
    }

    public function selectInput($name = '', $options = [], $selectedValue = false, $params = [])
    {
        $nameHtml = $this->partHtmlName($name);
        $paramsHtml = $this->partHtmlParams($params);

        $html = '<select' . $nameHtml . $paramsHtml . '>';

        foreach ($options as $opt) {
            $html .= '<option';
            if (!empty($opt['value'])) {
                $selected = ($selectedValue == $opt['value']) ? ' selected="selected" ' : '';
                $html .= ' value="' . Safe::htmlEncode($opt['value']) . '"' . $selected;
            }
            $html .= '>' . Safe::htmlEncode($opt['name']) . '</option>';
        }

        $html .= '</select>';

        return $html;
    }
}
