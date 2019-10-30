<?php

/* @var $this app\core\View */
/* @var $values array */

$this->values->title = 'Список городов';

?>
<main>
    <?= $this->render(__DIR__ . '/_edit-list.php', $values) ?>
</main>
