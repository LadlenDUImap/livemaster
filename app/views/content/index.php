<?php
/* @var $this app\core\View */
/* @var $users array */

$this->title = 'Список пользователей';

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
        <div class="t-row">
            <div class="col col-3">10</div>
            <div class="col col-3">Дима Долгов Станиславович</div>
            <div class="col col-3">45</div>
            <div class="col col-3">Нет города</div>
        </div>
    </div>
    <button id="btn-add-user">Добавить пользователя</button>
</main>

<script>
    $(function () {
        $("#btn-add-user").click(function () {
            
        });
    });
</script>
