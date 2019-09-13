<?php

namespace app\core;

use app\base\DatabaseRecord;

abstract class DatabaseRecordForm
{
    /** @var  \app\base\DatabaseRecord  */
    protected $model;


    public function __construct(DatabaseRecord $model)
    {
        $this->model = $model;
    }

    public function getModel(): DatabaseRecord
    {
        return $this->model;
    }
}
