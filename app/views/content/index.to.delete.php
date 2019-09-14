<?php
/* @var $this app\core\View */
/* @var $values array */

use app\core\html\Pattern;
use app\models\db\User;

$this->title = 'Список пользователей';

$users = $values['users'];

#$usr = new User;
#$prop = $usr->traverseProperties();

/*foreach (User::traverseProperties() as $tp) {
    $prop = $tp;
    print_r($prop);
}*/

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
    <div class="user-list" id="user-list">
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
            echo $userRowPattern->fillPatternWithValuesHtml($usr) . "\n";
        }
        ?>
    </div>
    <button id="btn-add-user">Добавить пользователя</button>
</main>

<script>
    $(function () {
        var userRowPatternNew = <?= $userRowPattern->fillPatternWithValuesJs([
            'id' => '&nbsp;',
            'name' => '<input type="text" class="new-input new-name">',
            'age' => '<input type="text" class="new-input new-age">',
            'city' => '<input type="text" class="new-input new-city">',
        ], false) ?>;

        $("#btn-add-user").click(function () {
            $(".user-list .t-row:last-child").after(userRowPatternNew);
        });
    });
</script>
