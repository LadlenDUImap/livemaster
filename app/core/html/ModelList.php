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

    private $firstInit;


    public function __construct($templateElements = [])
    {
        $this->templateElements = array_replace_recursive($this->templateElements, $templateElements);

        if (!$this->firstInit) {
            $this->registerJs();
            $this->firstInit = true;
        }
    }

    protected function registerJs()
    {
        LM::inst()->getController()->getView()->addJsCode(<<<JS
(function() {
    var lastModifiedInfo;
    
    $(".ml-overlap-edit-element").click(function() {
        if (lastModifiedInfo) {
            lastModifiedInfo['elem-edit-wrapper'].hide();
            lastModifiedInfo['elem-overlap'].show();
            lastModifiedInfo['elem-edit'].blur();
        }
        
        var elemOverlap = $(this);
        var elemEditWrapper = elemOverlap.next(".ml-hidden-edit-element-wrapper");
        elemOverlap.hide();
        elemEditWrapper.show();
        elemEdit = elemEditWrapper.find('.ml-hidden-edit-element');
        elemEdit.focus();
        
        lastModifiedInfo = {'elem-overlap':elemOverlap, 'elem-edit-wrapper':elemEditWrapper, 'elem-edit':elemEdit};
    });
    
    $(".ml-hidden-edit-element").change(function() {
        var currElem = $(this);
        var currentValue = (currElem.prop("tagName") == 'SELECT') ? $(this).text() : $(this).val();
        currElem.parent(".ml-hidden-edit-element-wrapper").prev(".ml-overlap-edit-element").text(currentValue);
    });
    
})();
JS
        );
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

        $params['class'] = $params['class'] ?? '';
        $params['class'] = ' ml-hidden-edit-element';

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

        $params['class'] = $params['class'] ?? '';
        $params['class'] = ' ml-hidden-edit-element';

        return $this->overlapElement($this->currentForm->selectInput($model, $attribute, $options, $params), $options[$model->$attribute ?? 0]['name']);
    }

    protected function overlapElement($elementHtml, $overlapText)
    {
        $html = '<div class="ml-overlap-edit-element">' . Safe::htmlEncode($overlapText) . '</div>'
            . '<div  class="ml-hidden-edit-element-wrapper" style="display:none;">'
            . $elementHtml
            . '</div>';

        return $html;
    }

}
