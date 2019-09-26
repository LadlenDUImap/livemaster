<?php

namespace app\core\html;

use app\base\DatabaseRecord;
use app\core\Lm;
use app\helpers\ClassHelper;
use app\helpers\HtmlHelper;

class Form
{
    /** @var int порядковый идентификатор следующей (или уже создающейся) формы */
    protected static $id = 1;


    public function begin($id = false)
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

    public function partHtmlName(DatabaseRecord $model, $attribute = '')
    {
        if ($model) {
            $nameHtml = ' name="' . ClassHelper::getClassNameNoNamespace($model) . '[' . Safe::htmlEncode($attribute) . ']' . '" ';
        } else {
            $nameHtml = $attribute ? ' name="' . Safe::htmlEncode($attribute) . '" ' : '';
        }

        return $nameHtml;
    }

    public function textInput(DatabaseRecord $model, $attribute = '', $params = [])
    {
        $nameHtml = $this->partHtmlName($model, $attribute);
        $paramsHtml = HtmlHelper::partHtmlParams($params);

        $valueHtml = '';
        if ($attribute) {
            $valueHtml = ' value="' . Safe::htmlEncode($model->$attribute) . '" ';
        }

        return '<input type="text"' . $nameHtml . $valueHtml . $paramsHtml . ' />';
    }

    public function selectInput(DatabaseRecord $model, $attribute = '', $options = [], $params = [])
    {
        $nameHtml = $this->partHtmlName($model, $attribute);
        $paramsHtml = HtmlHelper::partHtmlParams($params);

        //$selectedName = '';

        $html = '<select' . $nameHtml . $paramsHtml . '>';

        foreach ($options as $opt) {
            $html .= '<option';
            if (!empty($opt['value'])) {
                /*if ($selected = ($selectedValue == $opt['value']) ? ' selected="selected" ' : '') {
                    $selectedName = $opt['name'];
                }*/
                $selected = ($model->$attribute == $opt['value']) ? ' selected="selected" ' : '';
                $html .= ' value="' . Safe::htmlEncode($opt['value']) . '"' . $selected;
            }/* else {
                $selectedName = $opt['name'];
            }*/
            $html .= '>' . Safe::htmlEncode($opt['name']) . '</option>';
        }

        $html .= '</select>';

        //$html = '<div' . $paramsHtml . '>' . Safe::htmlEncode($selectedName) . '</div>' . $html;

        return $html;
    }
}
