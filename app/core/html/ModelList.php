<?php

namespace app\core\html;

use app\base\DatabaseRecord;
use app\core\Lm;

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
            // контейнер, в конец которого будет добавлен новый созданный элемент
            'element-container' => [
                'selector' => '#model-list-new-element-container',
            ],
            // кнопка добавления
            'add-button' => [
                'selector' => '.model-list-new-add-button',
            ],
            // кнопка удаления
            'delete-button' => [
                'selector' => '.model-list-delete-button',
            ],
        ],
    ];

    protected $actions = [
        'new' => 'new',
        'delete' => 'delete',
        'update' => 'update',
    ];


    /** @var  Form */
    private $currentForm;

    /** @var bool признак первой инициализации класса */
    private $firstInit = false;


    public function __construct($actions = [], $templateElements = [])
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
    
    function ajaxSave() {
      
    }
    
    $(".ml-hidden-edit-element").change(function() {
        var currElem = $(this);
        var currentValue = (currElem.prop("tagName") == 'SELECT') ? currElem.find("option:selected").text() : currElem.val();
        currElem.parent(".ml-hidden-edit-element-wrapper").prev(".ml-overlap-edit-element").text(currentValue);
    });
    
    $(".ml-hidden-edit-element").blur(function() {
        //alert(3);
    });
    
    $("#model-list-new-add-button").click(function() {
      
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
        $params = $this->prepareParamsForElement($model, $params);

        return $this->overlapElement($this->currentForm->textInput($model, $attribute, $params), $model->$attribute);
    }

    public function selectInput(DatabaseRecord $model, $attribute = '', $options = [], $params = [])
    {
        $params = $this->prepareParamsForElement($model, $params);

        return $this->overlapElement($this->currentForm->selectInput($model, $attribute, $options, $params),
            $options[$model->$attribute ?? 0]['name']);
    }

    protected function prepareParamsForElement(DatabaseRecord $model, $params)
    {
        $params['class'] = $params['class'] ?? '';
        $params['class'] .= ' ml-hidden-edit-element';
        $params['data-ml-id'] = $model->getId();

        return $params;
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
