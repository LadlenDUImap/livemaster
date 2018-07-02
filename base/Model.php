<?php

namespace base;

abstract class Model
{
    private $isNew = true;

    public function __construct($id = false)
    {
        if ($id) {
            LM::inst()
        }
    }

    public function getInstance($condition)
    {

    }

    public function getIsNew() {
        return $this->isNew;
    }
}
