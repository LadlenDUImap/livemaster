<?php

namespace app\helpers;

use app\base\DatabaseRecord;
use app\core\html\Form;
use app\core\html\Safe;

class HtmlHelper
{
    public static function partHtmlParams($params = [])
    {
        $paramsHtml = '';

        if ($params) {
            foreach ($params as $pName => $pVal) {
                $paramsHtml .= ' ' . $pName . '="' . Safe::htmlEncode($pVal) . '" ';
            }
        }

        return $paramsHtml;
    }

    public static function keys2HtmlModelName(DatabaseRecord $model, array $attributes)
    {
        $newAttributes = [];

        foreach ($attributes as $key => $value) {
            $newAttributes[Form::makeHtmlModelName($model, $key)] = $value;
        }

        return $newAttributes;
    }
}
