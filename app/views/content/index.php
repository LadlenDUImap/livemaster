<?php

/* @var $this app\core\View */
/* @var $values array */

use app\core\html\Form;

$this->title = 'Список пользователей';

$users = $values['users'];

?>
<main>
    <h3 class="header">Список пользователей</h3>
    <div class="container user-list" id="user-list">
        <div class="row head">
            <div class="col">Имя</div>
            <div class="col">Возраст</div>
            <div class="col">Город</div>
            <div class="col">#</div>
        </div>
        <?php
        if ($users) {
            $rowParams = ['style' => 'width:100%'];
            foreach ($users as $usr) {
                $form = new Form($usr);
                echo $form->start()
                    . '<div class="row item">'
                    . '<div class="col">' . $form->textInput('name', $rowParams) . '</div>'
                    . '<div class="col">' . $form->textInput('age', $rowParams) . '</div>'
                    . '<div class="col">' . $form->textInput('city_id', $rowParams) . '</div>'
                    . '<div class="col">#</div>'
                    . '</div>'
                    . $form->end();
            }
        }
        ?>
    </div>
    <button id="btn-add-user">Добавить пользователя</button>
</main>

<input type="hidden" id="next-form-id" value="<?= Form::getCurrentId() ?>"/>

<script>
    <?php /* ?>
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
 <?php */ ?>
</script>
