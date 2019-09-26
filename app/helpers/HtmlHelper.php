<?php

namespace app\helpers;

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
}
