<?php

namespace app\base;

abstract class Model
{
    private $isNew = true;

    public function __construct($id = false)
    {
        if ($id) {
            Lm::inst()
        }
    }

    public function getInstance($condition)
    {

    }

    public function getIsNew() {
        return $this->isNew;
    }
}
