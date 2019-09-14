<?php

namespace app\core\html;

use app\base\IProperties;

class Form
{
    /** @var  IProperties */
    protected $model;

    protected static $id = 0;


    public function __construct($model)
    {
        $this->model = $model;
    }

    public function start()
    {
        echo '<form id="lm_form_' . self::$id . '">';
    }

    public function end()
    {
        echo '</form>';
        ++self::$id;
    }
}
