<?php

namespace app\core\html;

class Pattern extends Safe
{
    protected $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public static function fillPatternWithValues($pattern, $values, $encodeFunction)
    {
        $filledPattern = $pattern;
        foreach ($values as $name => $val) {
            $valEncoded = $encodeFunction ? self::$encodeFunction($val) : $val;
            $filledPattern = str_replace('{' . $name . '}', $valEncoded, $filledPattern);
        }
        return $filledPattern;
    }

    public function fillPatternWithValuesHtml($values)
    {
        return self::fillPatternWithValues($this->pattern, $values, 'htmlEncode');

        /*$filledPattern = $this->pattern;
        foreach ($values as $name => $val) {
            $valEncoded = self::htmlEncode($val);
            $filledPattern = str_replace('{' . $name . '}', $valEncoded, $filledPattern);
        }
        return $filledPattern;*/
    }

    public function fillPatternWithValuesJs($values, $encode = 'htmlEncode')
    {
        return self::jsEncode(self::fillPatternWithValues($this->pattern, $values, $encode));
    }

    /*public static function php2NewHtmlPattern($html)
    {
        return preg_replace('/\{\S+\}/', '', $html);
    }*/
}
