<?php

namespace app\core\html;

use app\base\DatabaseRecord;
use app\core\Lm;
use app\helpers\ClassHelper;
use app\helpers\HtmlHelper;

class ModelList
{
    /** @var array параметры, описывающие значения html элементов обрабатываемого шаблона */
    protected $templateElements = [
        // все что касается нового элемента
        'new' => [
            // контейнер с шаблоном для нового элемента
            'template-container' => [
                // CSS селектор
                'selector' => '#model-list-new-element-template',
            ],
            // кнопка добавления
            'add-button' => [
                'selector' => '#model-list-new-add-button',
            ],
            // контейнер, в конец которого будет добавлен новый созданный элемент
            'element-container' => [
                'selector' => '#model-list-new-element-container',
            ],
        ],
    ];


    /** @var  Form */
    private $currentForm;


    public function __construct($templateElements = [])
    {
        $this->templateElements = array_replace_recursive($this->templateElements, $templateElements);

        //LM::inst()->getController()->getView()->addJsCode('alert(1)');
    }

    public function beginElement()
    {
        $this->currentForm = new Form();
        return $this->currentForm->begin();
    }

    public function endElement()
    {
        $endHtml = $this->currentForm->end();
        unset($this->currentForm);
        return $endHtml;
    }

    public function textInput(DatabaseRecord $model, $attribute = '', $params = [])
    {
        //$params['readonly'] = 'readonly';
        //$params['class'] = $params['class'] ?? '';
        //$params['class'] .= ' ml-clicked-elem ml-readonly';

        return $this->overlapElement($this->currentForm->textInput($model, $attribute, $params), $model->$attribute);
    }

    public function selectInput(DatabaseRecord $model, $attribute = '', $options = [], $selectedValue = false, $params = [])
    {
        /*$selectedName = '';
        $paramsHtml = HtmlHelper::partHtmlParams([]);

        $html = '<div style="display:none;">';
        $html .= $this->currentForm->selectInput($model, $attribute, $options, $selectedValue, $params);
        $html .= '</div>';

        $html = '<div' . $paramsHtml . '>' . Safe::htmlEncode($selectedName) . '</div>' . $html;

        return $html;*/

        return $this->overlapElement($this->currentForm->selectInput($model, $attribute, $options, $params), $options[$model->$attribute ?? 0]['name']);
    }

    protected function overlapElement($elementHtml, $overlapText)
    {
        $html = '<div class="ml-overlap-edit-element">' . Safe::htmlEncode($overlapText) . '</div>'
            . '<div  class="ml-hidden-edit-element" style="display:none;">'
            . $elementHtml
            . '</div>';

        return $html;
    }

}
