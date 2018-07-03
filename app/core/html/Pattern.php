<?php

namespace app\core\html;

class Pattern
{
    public static function php2NewHtmlPattern($phpHtml)
    {
        return preg_replace('/\{\S+\}/', '', $phpHtml);
    }
}
