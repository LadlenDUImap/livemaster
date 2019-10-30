<?php

use app\core\Url;

/* @var $this app\core\View */
/* @var $values array */

$this->values->title = 'Список пользователей';

?>
<main>
    <?= $this->render(__DIR__ . '/user/_edit-list.php', $values) ?>
</main>
