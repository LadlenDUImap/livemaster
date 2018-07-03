<?php
/* @var $this app\core\View */
/* @var $users array */

$this->title = 'Список пользователей';

$userRowPattern = <<<HTML
<div class="t-row">
    <div class="col col-3">{id}</div>
    <div class="col col-3">{name}</div>
    <div class="col col-3">{age}</div>
    <div class="col col-3">{city}</div>
</div>
HTML;

?>
<main>
    <h3 class="header">Список пользователей</h3>
    <div class="user-list">
        <div class="t-row head">
            <div class="col col-3">ID</div>
            <div class="col col-3">Имя</div>
            <div class="col col-3">Возраст</div>
            <div class="col col-3">Город</div>
        </div>
        <?php
        foreach ($users as $usr) {
            str_replace('{id}', '', $userRowPattern);
        }
        ?>
    </div>
    <button id="btn-add-user">Добавить пользователя</button>
</main>

<script>
    $(function () {
        $("#btn-add-user").click(function () {

        });
    });
</script>
