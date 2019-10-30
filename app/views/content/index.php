<?php

/* @var $this app\core\View */
/* @var $values array */

$this->values->title = 'Список пользователей';

?>
<nav>
    <ul>
        <li><a href="/">Пользователи</a></li>
        <li><a href="/city">Города</a></li>
    </ul>
</nav>
<main>
    <?= $this->render(__DIR__ . '/user/_edit-list.php', $values) ?>
</main>
