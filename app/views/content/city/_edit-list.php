<?php

use app\core\Url;
use app\core\html\ModelList;

/* @var $this app\core\View */
/* @var $values array */

$cities = [];
foreach ($values['cities'] as $city) {
    $cities[] = [
        'name' => $city->name,
        'value' => $city->id,
    ];
}

$cityList = new ModelList([
    'create' => Url::to('/city/create'),
    'delete' => Url::to('/city/delete'),
    'update' => Url::to('/city/update'),
]);

?>
<div id="ml-new-element-template" style="display: none"><?php

    $rowParams = [
        'style' => 'width:100%;text-align:center',
    ];
    $city = new \app\models\db\City();
    $form = new \app\core\html\Form();
    echo $form->begin(0)
        . '<section class="row item">'
        . '<div class="col">' . $form->textInput($city, 'name', $rowParams) . '</div>'
        . '<div class="col">'
        . '<button class="ml-new-save-button">Сохранить</button><button class="ml-new-save-cancel-button">Отменить</button>'
        . '</div>'
        . '</section>'
        . $form->end();
    ?></div>

<header><h3 class="header">Список городов</h3></header>

<div class="row head">
    <div class="col">Название</div>
    <div class="col">&nbsp;</div>
</div>

<div class="body" id="ml-new-element-container">
    <?php
    if ($cities) {
        foreach ($cities as $city) {
            echo $this->render(__DIR__ . '/_row.php', compact('cityList', 'city'));
        }
    } else {
        echo '<div class="row item" style="padding: 10px;font-weight: bold"><div class="col">Нет городов</div></div>';
    }
    ?>
</div>

<button id="btn-add-user" class="ml-new-add-button">Добавить город</button>
