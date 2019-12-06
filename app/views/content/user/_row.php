<?php

/* @var $this app\core\View */
/* @var $values array */

$rowParams = [
    'style' => 'width:100%;text-align:center',
    'maxlength' => 30,
];

echo $values['userList']->beginElement($values['user']->getId(), ['class' => 'div-table-row item'])
    //. '<section class="row item">'
    . '<div class="div-table-col">' . $values['userList']->textInput($values['user'], 'name', $rowParams) . '</div>'
    . '<div class="div-table-col">' . $values['userList']->textInput($values['user'], 'age', $rowParams) . '</div>'
    . '<div class="div-table-col">' . $values['userList']->selectInput($values['user'], 'city_id', $values['cities'], $rowParams) . '</div>'
    . '<div class="div-table-col"><button class="ml-delete-button">Удалить</button></div>'
    //. '</section>'
    . $values['userList']->endElement();
