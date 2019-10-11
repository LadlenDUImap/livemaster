<?php

namespace app\core\html;

use app\base\DatabaseRecord;
use app\core\Lm;

class ModelList
{
    /** @var array параметры, описывающие значения html элементов обрабатываемого шаблона */
    protected $_templateElements = [
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

    protected $_actions = [
        'create' => 'create',
        'delete' => 'delete',
        'update' => 'update',
    ];


    /** @var  Form */
    private $_currentForm;

    /** @var bool признак первой инициализации класса */
    private static $_firstInit = true;


    public function __construct($actions = [], $templateElements = [])
    {
        $this->_actions = $actions;

        $this->_templateElements = array_replace_recursive($this->_templateElements, $templateElements);

        if (self::$_firstInit) {
            $this->registerJs();
            self::$_firstInit = false;
        }
    }

    protected function registerJs()
    {
        $templateElements = json_encode($this->_templateElements);

        $actions = json_encode($this->_actions);

        LM::inst()->getController()->getView()->addJsCode(<<<JS
(function() {
    var lastModifiedInfo;
    
    var templateElements = $templateElements;
    var actions = $actions;
    
    var jQueryElements = {".ml-hidden-edit-element":$(".ml-hidden-edit-element")};
    
    $(".ml-overlap-edit-element").click(function() {
        var elemOverlap = $(this);
        var elemEditWrapper = elemOverlap.next(".ml-hidden-edit-element-wrapper");
        elemOverlap.hide();
        elemEditWrapper.show();
        elemEdit = elemEditWrapper.find(".ml-hidden-edit-element");
        elemEdit.focus();
        
        lastModifiedInfo = {"elem-overlap":elemOverlap, "elem-edit-wrapper":elemEditWrapper, "elem-edit":elemEdit};
    });
    
    jQueryElements[".ml-hidden-edit-element"].change(function() {
        var currElem = $(this);
        var currentValue = (currElem.prop("tagName") == 'SELECT') ? currElem.find("option:selected").text() : currElem.val();
        currElem.parent(".ml-hidden-edit-element-wrapper").prev(".ml-overlap-edit-element").text(currentValue);
    });
    
    jQueryElements[".ml-hidden-edit-element"].keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $(this).blur();
            return false;
        }
    });
    
    jQueryElements[".ml-hidden-edit-element"].blur(function() {
        updateElement($(this));
    });
    
    templateElements["new"]["element-container"]["elem"] = $(templateElements["new"]["element-container"]["selector"]);
    
    $(templateElements["new"]["add-button"]["selector"]).click(function() {
        var elemContainer = templateElements["new"]["element-container"]["elem"];
        if (elemContainer.data("newElementProcessing") != "in-process") {
            elemContainer.data("newElementProcessing", "in-process");
            var html = $(templateElements["new"]["template-container"]["selector"]).html();
            elemContainer.append('<div class="ml-new-element-wrapper">' + html + '</div>');
            addNewElementProcessStart();
        } else {
            alert("Новый элемент уже в процессе создания.");
        }
    });
    
    $(templateElements["delete-button"]["selector"]).click(function() {
        var formElem = $(this).closest('form');
        deleteElement(formElem);
        return false;
    });
    
    function addNewElementProcessStart() {
        $(templateElements["new"]["save-cancel-button"]["selector"]).unbind().click(function() {
            $(this).closest(".ml-new-element-wrapper").remove();
            templateElements["new"]["element-container"]["elem"].data("newElementProcessing", "no-process");
            return false;
        });
        
        $(".ml-new-element-wrapper form").unbind().submit(function() {
            var formElem = $(this);
            var data = formElem.serialize();
            $.post(actions["create"], data, function(data) {
                if (data) {
                    if (data.state == "success") {
                       alert('Элемент успешно добавлен.');
                       location.reload(true);
                    } else if (data.state == "error") {
                        alert(Utils.assocArrayJoin(data.data["error-messages"], "\\n"));
                    }
                    if (data.data && data.data["corrected-attributes"]) {
                        $.each(data.data["corrected-attributes"], function(id, val) {
                            formElem.find('[name="' + id + '"]').val(val);
                        });
                    }
                }
            }).fail(function(jqXHR, textStatus, error) {
                alert("Ошибка на сервере " + jqXHR.status + ": " + error);
            });
            
            return false;
        });
    }
    
    function updateElement(elem) {
        /*if (lastModifiedInfo) {
            lastModifiedInfo["elem-edit-wrapper"].hide();
            lastModifiedInfo["elem-overlap"].show();
            //lastModifiedInfo["elem-edit"].blur();
        }*/
        var formElem = elem.closest('form');
        var data = formElem.serialize();
        $.post(actions["update"], data, function(data) {
            if (data) {
                if (data.state == "success") {
                   if (lastModifiedInfo) {
                        lastModifiedInfo["elem-edit-wrapper"].hide();
                        lastModifiedInfo["elem-overlap"].show();
                        //lastModifiedInfo["elem-edit"].blur();
                    }
                } else if (data.state == "error") {
                    alert(Utils.assocArrayJoin(data.data["error-messages"], "\\n"));
                    elem.focus();
                }
                if (data.data && data.data["corrected-attributes"]) {
                    $.each(data.data["corrected-attributes"], function(id, val) {
                        formElem.find('[name="' + id + '"]').val(val);
                    });
                }
            }
        }).fail(function(jqXHR, textStatus, error) {
            alert("Ошибка на сервере " + jqXHR.status + ": " + error);
        });
    }
    
    function deleteElement(formElem) {
        formElem.css({"background-color":"red","color":"white"});
        if (confirm("Действительно хотите удалить выделенный элемент?")) {
            //var id = formElem.find("[name='lm_form_id']").val();
            var data = formElem.serialize();
            //$.post(actions["delete"] + '?' + $.param({id:id}), function(data) {
            $.post(actions["delete"], data, function(data) {
                if (data) {
                    if (data.state == "success") {
                       alert("Элемент успешно удалён.");
                       formElem.remove();
                    } else if (data.state == "error") {
                        alert(Utils.assocArrayJoin(data.data["error-messages"], "\\n"));
                    }
                }
            }).fail(function(jqXHR, textStatus, error) {
                alert("Ошибка на сервере " + jqXHR.status + ": " + error);
            }).always(function() {
                formElem.css({"background-color":"white","color":"black"});              
            });
        } else {
            formElem.css({"background-color":"white","color":"black"});
        }
    }
})();
JS
        );
    }

    public function beginElement($id)
    {
        $this->_currentForm = new Form();
        return $this->_currentForm->begin($id);
    }

    public function endElement()
    {
        $endHtml = $this->_currentForm->end();
        unset($this->_currentForm);
        return $endHtml;
    }

    public function textInput(DatabaseRecord $model, $attribute = '', $params = [])
    {
        $params = $this->prepareParamsForElement($model, $params);

        return $this->overlapElement($this->_currentForm->textInput($model, $attribute, $params), $model->$attribute);
    }

    public function selectInput(DatabaseRecord $model, $attribute = '', $options = [], $params = [])
    {
        $params = $this->prepareParamsForElement($model, $params);

        return $this->overlapElement($this->_currentForm->selectInput($model, $attribute, $options, $params),
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
