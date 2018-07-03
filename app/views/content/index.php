<?php
/* @var $this app\core\View */
/* @var $values array */

use app\core\html\Pattern;

$this->title = 'Список пользователей';

$users = $values['users'];

$userRow = <<<HTML
<div class="t-row">
    <div class="col col-3">{id}</div>
    <div class="col col-3">{name}</div>
    <div class="col col-3">{age}</div>
    <div class="col col-3">{city}</div>
</div>
HTML;

$userRowPattern = new Pattern($userRow);

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
            /*$userRowPatternMod = str_replace('{id}', $usr['id'], $userRowPattern);
            $userRowPatternMod = str_replace('{name}', $usr['name'], $userRowPatternMod);
            $userRowPatternMod = str_replace('{age}', $usr['age'], $userRowPatternMod);
            $userRowPatternMod = str_replace('{city}', $usr['city'], $userRowPatternMod);*/
            echo $userRowPattern->fillPatternWithValues($usr) . "\n";
        }
        ?>
    </div>
    <button id="btn-add-user">Добавить пользователя</button>
</main>

<script>
    $(function () {
        var userRowPatternNew = <?= $userRowPattern->getEmptyJsPattern() ?>;

        $("#btn-add-user").click(function () {
            $(".user-list .t-row:last-child").after(userRowPatternNew);
        });
    });
</script>
