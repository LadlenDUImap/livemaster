<?php

use app\core\Url;
use app\core\html\ModelList;

/* @var $this app\core\View */
/* @var $values array */

$users = $values['users'];
$cities[0] = [
    'name' => '-- Город не выбран --',
    'value' => 0,
];
foreach ($values['cities'] as $city) {
    $cities[$city->id] = [
        'name' => $city->name,
        'value' => $city->id,
    ];
}

$userList = new ModelList([
    'create' => Url::to('/user/create'),
    'delete' => Url::to('/user/delete'),
    'update' => Url::to('/user/update'),
]);

?>
<div id="ml-new-element-template" style="display: none"><?php

    $rowParams = [
        'style' => 'width:100%;text-align:center',
    ];
    $user = new \app\models\db\User();
    $form = new \app\core\html\Form();
    echo $form->begin(0, ['class' => 'div-table-row item'])
        //. '<section class="row item">'
        . '<div class="div-table-col">' . $form->textInput($user, 'name', $rowParams) . '</div>'
        . '<div class="div-table-col">' . $form->textInput($user, 'age', $rowParams) . '</div>'
        . '<div class="div-table-col">' . $form->selectInput($user, 'city_id', $cities, $rowParams) . '</div>'
        . '<div class="div-table-col">'
        . '<button class="ml-new-save-button">Сохранить</button><button class="ml-new-save-cancel-button">Отменить</button>'
        . '</div>'
        //. '</section>'
        . $form->end();
    ?></div>

<header><h3 class="header">Список пользователей<span class="users-info-str"></span></h3></header>

<div class="div-table">
    <div class="div-table-heading">
        <div class="div-table-row head">
            <div class="div-table-col">Имя</div>
            <div class="div-table-col">Возраст</div>
            <div class="div-table-col">Город</div>
            <div class="div-table-col">&nbsp;</div>
        </div>
    </div>

    <div class="div-table-body" id="ml-new-element-container">
        <?php
        if ($users) {
            foreach ($users as $user) {
                echo $this->render(__DIR__ . '/_row.php', compact('userList', 'user', 'cities'));
            }
        } else {
            //echo '<div class="div-table-row item" style="padding: 10px;font-weight: bold"><div class="col">Нет пользователей</div></div>';
            $this->addJsCode(<<<JS
            (function() {
              $(".users-info-str").text(' - нет пользователей');
            })();
JS
            );
        }
        ?>
    </div>
</div>

<button id="btn-add-user" class="ml-new-add-button">Добавить пользователя</button>
