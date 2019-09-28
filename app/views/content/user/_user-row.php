<?php

/* @var $this app\core\View */
/* @var $values array */

$rowParams = [
    'style' => 'width:100%;text-align:center',
];

echo $values['cityList']->beginElement()
    . '<div class="row item">'
    . '<div class="col">' . $values['cityList']->textInput($values['user'], 'name', $rowParams) . '</div>'
    . '<div class="col">' . $values['cityList']->textInput($values['user'], 'age', $rowParams) . '</div>'
    . '<div class="col">' . $values['cityList']->selectInput($values['user'], 'city_id', $values['cities'], $rowParams) . '</div>'
    . '<div class="col"><button class="ml-delete-button">Удалить</button></div>'
    . '</div>'
    . $values['cityList']->endElement();
