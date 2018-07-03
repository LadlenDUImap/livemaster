<?php

namespace app\core\html;

class Pattern extends Safe
{
    protected $pattern;

    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    public function fillPatternWithValues($values)
    {
        $filledPattern = $this->pattern;
        foreach ($values as $name => $val) {
            $valEncoded = self::htmlEncode($val);
            $filledPattern = str_replace('{' . $name . '}', $valEncoded, $filledPattern);
        }
        return $filledPattern;
    }

    public function getEmptyJsPattern()
    {
        return self::jsEncode(self::php2NewHtmlPattern($this->pattern));
    }

    public static function php2NewHtmlPattern($html)
    {
        return preg_replace('/\{\S+\}/', '', $html);
    }
}
