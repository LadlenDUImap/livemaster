<?php

use app\core\html\ModelList;

/* @var $this app\core\View */
/* @var $values array */

$this->title = 'Список пользователей';

$users = $values['users'];
$cities[] = [
    'name' => '-- Город не выбран --',
    'value' => 0,
];
foreach ($values['cities'] as $city) {
    $cities[] = [
        'name' => $city->name,
        'value' => $city->id,
    ];
}

$cityList = new ModelList();

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
            $rowParams = [
                'class' => 'clicked-elem',
                'style' => 'width:100%;text-align:center;border:none;',
                //'readonly' => 'readonly'
            ];
            foreach ($users as $usr) {
                echo $cityList->beginElement()
                    . '<div class="row item">'
                    //. '<input type="hidden" class="elem-id" value="' . $usr->id . '">'
                    //. '<input type="hidden" class="row-id" value="' . $cityList::getCurrentId() . '">'
                    . '<div class="col">' . $cityList->textInput($usr, 'name', $rowParams) . '</div>'
                    . '<div class="col">' . $cityList->textInput($usr, 'age', $rowParams) . '</div>'
                    . '<div class="col">' . $cityList->selectInput($usr, 'city_id', $cities, $usr->city_id, $rowParams) . '</div>'
                    . '<div class="col"><button>Удалить</button></div>'
                    . '</div>'
                    . $cityList->endElement();
            }
        }
        ?>

        <div class="row item">
        </div>

    </div>
    <button id="btn-add-user">Добавить пользователя</button>
</main>

<!--<input type="hidden" id="next-form-id" value="<?/*= Form::getCurrentId() */?>"/>-->

<script>
    $('.clicked-elem').click(function (elem) {
        var elemId = $(this).parents(':eq(1)').find(".elem-id");
        //alert("elemId: " + elemId.val());
        /*if () {

        }*/
    });
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
