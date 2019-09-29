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
            // контейнер с шаблоном с новым элементом
            'template-container' => [
                // CSS селектор
                'selector' => '#ml-new-element-template',
            ],
            // контейнер, в конец которого будет добавлен новый созданный элемент
            'element-container' => [
                'selector' => '#ml-new-element-container',
            ],
            // кнопка добавления (для начала процесса добавления)
            'add-button' => [
                'selector' => '.ml-new-add-button',
            ],
            // кнопка сохранения нового элемента после заполнения
            // закомментировал т. к. будет использоваться событие submit формы
            /*'save-button' => [
                'selector' => '.ml-new-save-button',
            ],*/
            // кнопка сохранения нового элемента после заполнения
            'save-cancel-button' => [
                'selector' => '.ml-new-save-cancel-button',
            ],
        ],
        // кнопка удаления
        'delete-button' => [
            'selector' => '.ml-delete-button',
        ],

    ];

    protected $actions = [
        'create' => 'create',
        'delete' => 'delete',
        'update' => 'update',
    ];


    /** @var  Form */
    private $currentForm;

    /** @var bool признак первой инициализации класса */
    private static $firstInit = true;


    public function __construct($actions = [], $templateElements = [])
    {
        $this->actions = $actions;

        $this->templateElements = array_replace_recursive($this->templateElements, $templateElements);

        if (self::$firstInit) {
            $this->registerJs();
            self::$firstInit = false;
        }
    }

    protected function registerJs()
    {
        $templateElements = json_encode($this->templateElements);

        $actions = json_encode($this->actions);

        LM::inst()->getController()->getView()->addJsCode(<<<JS
(function() {
    var lastModifiedInfo;
    
    var templateElements = $templateElements;
    var actions = $actions;
    
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
    
    
    templateElements['new']['element-container']['elem'] = $(templateElements['new']['element-container']['selector']);
    
    $(templateElements['new']['add-button']['selector']).click(function() {
        var elemContainer = templateElements['new']['element-container']['elem'];
        if (elemContainer.data('newElementProcessing') != 'in-process') {
            elemContainer.data('newElementProcessing', 'in-process');
            var html = $(templateElements['new']['template-container']['selector']).html();
            elemContainer.append('<div class="ml-new-element-wrapper">' + html + '</div>');
            addNewElementProcessStart();
        } else {
            alert('Новый элемент уже в процессе создания.');
        }
    });
    
    function addNewElementProcessStart() {
        $(templateElements['new']['save-cancel-button']['selector']).unbind().click(function() {
            $(this).closest(".ml-new-element-wrapper").remove();
            templateElements['new']['element-container']['elem'].data('newElementProcessing', 'no-process');
            return false;
        });
        
        $(".ml-new-element-wrapper form").unbind().submit(function() {
            var data = $(this).serialize();
            $.post(actions['create'], data, function(data) {
                alert(data);
            }).fail(function(jqXHR, textStatus, error) {
                alert("Ошибка на серере " + jqXHR.status + ": " + error);
            });
            
            return false;
        });
    }
    
    
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
