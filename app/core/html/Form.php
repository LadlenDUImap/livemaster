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
        return '<form class="lm_form_' . $id . '">'
            . '<input type="hidden" name="' . Lm::inst()->csrf->getCsrfTokenName() . '" value="' . Safe::htmlEncode(Lm::inst()->csrf->getCsrfToken()) . '" />';
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

        $html = '<select' . $nameHtml . $paramsHtml . '>';

        foreach ($options as $opt) {
            $html .= '<option';
            if (!empty($opt['value'])) {
                $selected = ($model->$attribute == $opt['value']) ? ' selected="selected" ' : '';
                $html .= ' value="' . Safe::htmlEncode($opt['value']) . '"' . $selected;
            }
            $html .= '>' . Safe::htmlEncode($opt['name']) . '</option>';
        }

        $html .= '</select>';

        return $html;
    }
}
