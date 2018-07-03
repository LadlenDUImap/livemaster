<?php

namespace app\core\traits;

trait SafeHtmlTrait
{
    public function __get($name)
    {
        return htmlspecialchars($name, ENT_QUOTES);
    }
}
