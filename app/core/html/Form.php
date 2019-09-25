<?php

namespace app\core\html;

use app\base\DatabaseRecord;
use app\core\Lm;
use app\helpers\ClassHelper;

class Form
{
    /** @var  DatabaseRecord */
    //TODO: остановился. Убрать и вынести в параметры
    protected $model;


    public function __construct($model)
    {
        $this->model = $model;
    }

    public function start()
    {
        $id = Safe::htmlEncode($this->model->getId());
        return '<form id="lm_form_' . $id . '">'
            . '<input type="hidden" id="lm_form_csrf_name_' . $id . '" value="' . Lm::inst()->csrf->getCsrfTokenName() . '" />'
            . '<input type="hidden" id="lm_form_csrf_value_' . $id . '" value="' . Lm::inst()->csrf->getCsrfToken() . '" />';
    }

    public function end()
    {
        return '</form>';
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

    //TODO: не $name а attribute
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

        $selectedName = '';

        $html = '<div style="display:none;"><select' . $nameHtml . $paramsHtml . '>';

        foreach ($options as $opt) {
            $html .= '<option';
            if (!empty($opt['value'])) {
                if ($selected = ($selectedValue == $opt['value']) ? ' selected="selected" ' : '') {
                    $selectedName = $opt['name'];
                }
                $html .= ' value="' . Safe::htmlEncode($opt['value']) . '"' . $selected;
            } else {
                $selectedName = $opt['name'];
            }
            $html .= '>' . Safe::htmlEncode($opt['name']) . '</option>';
        }

        $html .= '</select></div>';

        $html = '<div' . $paramsHtml . '>' . Safe::htmlEncode($selectedName) . '</div>' . $html;

        return $html;
    }
}
