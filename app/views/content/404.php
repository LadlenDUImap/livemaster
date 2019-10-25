<?php
/* @var $this app\core\View */

$this->title = 'Error 404. Страница не найдена.';
?>
<div class="global_message"><?= \app\core\html\Safe::htmlEncode($this->title) ?>
    <br>
    <a href="/">Домой</a>
</div>