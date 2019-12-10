<?php

use \app\core\Lm;
use app\core\html\ModelList;

/* @var $this app\core\View */
/* @var $values array */

$cityList = new ModelList([
    'create' => Lm::$app->url->to('/city/create'),
    'delete' => Lm::$app->url->to('/city/delete'),
    'update' => Lm::$app->url->to('/city/update'),
]);

?>
<div id="ml-new-element-template" style="display: none"><?php

    $rowParams = [
        'style' => 'width:100%;text-align:center',
    ];
    $city = new \app\models\db\City();
    $form = new \app\core\html\Form();
    echo $form->begin(0, ['class' => 'div-table-row item'])
        //. '<section class="div-table-row item">'
        . '<div class="div-table-col">' . $form->textInput($city, 'name', $rowParams) . '</div>'
        . '<div class="div-table-col">'
        . '<button class="ml-new-save-button">Сохранить</button><button class="ml-new-save-cancel-button">Отменить</button>'
        . '</div>'
        //. '</section>'
        . $form->end();
    ?></div>

<header><h3 class="header">Список городов<span class="cities-info-str"></span></h3></header>

<div class="div-table">
    <div class="div-table-heading">
        <div class="div-table-row head">
            <div class="div-table-head">Название</div>
            <div class="div-table-head">&nbsp;</div>
        </div>
    </div>

    <div class="div-table-body" id="ml-new-element-container">
        <?php
        if ($values['cities']) {
            foreach ($values['cities'] as $city) {
                echo $this->render(__DIR__ . '/_row.php', compact('cityList', 'city'));
            }
        } else {
            //echo '<div class="div-table-row item" style="padding: 10px;font-weight: bold"><div class="col">Нет городов</div></div>';
            $this->addJsCode(<<<JS
            (function() {
              $(".cities-info-str").text(' - нет городов');
            })();
JS
            );
        }
        ?>
    </div>
</div>

<button id="btn-add-user" class="ml-new-add-button">Добавить город</button>
