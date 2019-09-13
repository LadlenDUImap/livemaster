<?php

namespace app\models;

class UserHtml extends \app\models\db\User
{
    use \app\core\traits\SafeHtmlTrait;
}
