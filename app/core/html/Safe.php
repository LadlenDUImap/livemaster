<?php

namespace app\core\html;

/**
 * Class Safe
 * Для безопасной работы с html(js) кодом.
 *
 * @package app\core\html
 */
class Safe
{
    /**
     * Кодировать строку для размещения в HTML коде
     *
     * @param $html
     * @return string
     */
    public static function htmlEncode($html)
    {
        return htmlspecialchars($html, ENT_QUOTES);
    }

    /**
     * Кодировать строку для размещения в JS коде
     *
     * @param string $htmlJs
     * @return string
     */
    public static function jsEncode($htmlJs)
    {
        return json_encode($htmlJs);
    }
}
