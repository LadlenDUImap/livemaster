<?php

use app\core\html\ModelList;

/* @var $this app\core\View */
/* @var $values app\core\Container */

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

$cityList = new ModelList([
    'action' => [
        'new' => \app\core\Url::to('/user/new'),
        'delete' => \app\core\Url::to('/user/delete'),
        'update' => \app\core\Url::to('/user/update'),
    ],
]);

?>
<div id="model-list-new-element-template" style="display: none">
</div>

<main>
    <h3 class="header">Список пользователей</h3>
    <div class="container">

        <div class="row head">
            <div class="col">Имя</div>
            <div class="col">Возраст</div>
            <div class="col">Город</div>
            <div class="col">&nbsp;</div>
        </div>

        <div class="body">
            <?php
            if ($users) {
                $rowParams = [
                    'style' => 'width:100%;text-align:center',
                ];
                foreach ($users as $user) {
                    $this->render('_user-row', new \app\core\Container(compact('cityList', 'user', 'cities')));
                    /*echo $cityList->beginElement()
                        . '<div class="row item">'
                        . '<div class="col">' . $cityList->textInput($usr, 'name', $rowParams) . '</div>'
                        . '<div class="col">' . $cityList->textInput($usr, 'age', $rowParams) . '</div>'
                        . '<div class="col">' . $cityList->selectInput($usr, 'city_id', $cities, $rowParams) . '</div>'
                        . '<div class="col"><button class="model-list-delete-button">Удалить</button></div>'
                        . '</div>'
                        . $cityList->endElement();*/
                }
            } else {
                echo '<div class="row item" style="padding: 10px;font-weight: bold"><div class="col">Нет пользователей</div></div>';
            }
            ?>
        </div>

    </div>

    <button id="btn-add-user" class="model-list-new-add-button">Добавить пользователя</button>
</main>
