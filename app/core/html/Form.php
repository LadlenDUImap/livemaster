<?php

namespace app\core\html;

use app\base\DatabaseRecord;
use app\core\Lm;
use app\helpers\ClassHelper;
use app\helpers\HtmlHelper;

class Form
{
    public function begin($id, $props = [])
    {
        $classAdditional = isset($props['class']) ? (' ' . $props['class']) : '';

        return '<form class="lm_form_' . $id . $classAdditional . '">'
            . '<input type="hidden" name="lm_form_id" value="' . Safe::htmlEncode($id) . '" />'
            . '<input type="hidden" name="' . Lm::$app->csrf->getCsrfTokenName() . '" value="' . Safe::htmlEncode(Lm::$app->csrf->getCsrfToken()) . '" />';
    }

    public function end()
    {
        return '</form>';
    }

    public static function extractModelAttributesFromHtmlAttributes(DatabaseRecord $model, array $attributes): array
    {
        return $attributes[(new \ReflectionClass($model))->getShortName()];
    }

    public static function makeHtmlModelName(DatabaseRecord $model, string $attribute): string
    {
        return (new \ReflectionClass($model))->getShortName() . '[' . Safe::htmlEncode($attribute) . ']';
    }

    public function partHtmlName(DatabaseRecord $model, $attribute = ''): string
    {
        if ($model) {
            $nameHtml = ' name="' . self::makeHtmlModelName($model, $attribute) . '" ';
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
            if (isset($opt['value'])) {
                $selected = ($model->$attribute == $opt['value']) ? ' selected="selected" ' : '';
                $html .= ' value="' . Safe::htmlEncode($opt['value']) . '"' . $selected;
            }
            $html .= '>' . Safe::htmlEncode($opt['name']) . '</option>';
        }

        $html .= '</select>';

        return $html;
    }
}
