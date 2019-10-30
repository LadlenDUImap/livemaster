<?php
/* @var $this app\core\View */
echo $this->uusl;
$this->values->title = 'Error 404. Страница не найдена.';
?>
<div class="global_message"><?= $this->values->title ?>
    <br>
    <a href="/">Домой</a>
</div>