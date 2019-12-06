<?php

/* @var $this app\core\View */
/* @var $values array */

$rowParams = [
    'style' => 'width:100%;text-align:center',
    //'maxlength' => 30,
];

echo //'<section class="row item">'
    $values['cityList']->beginElement($values['city']->getId(), ['class' => 'row item'])
    . '<div class="col">' . $values['cityList']->textInput($values['city'], 'name', $rowParams) . '</div>'
    . '<div class="col"><button class="ml-delete-button">Удалить</button></div>'
    . $values['cityList']->endElement();
//. '</section>';
