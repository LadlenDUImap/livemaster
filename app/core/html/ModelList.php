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
            // кнопка отмены сохранения нового элемента после заполнения
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

        Lm::inst()->getController()->getView()->addCssCode(<<<CSS
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    /*background: transparent;*/
    background-color: #000;
    filter:alpha(opacity=10);
    -moz-opacity:0.1;
    -khtml-opacity: 0.1;
    opacity: 0.1;
    z-index: 10000;
}

.ml-hidden-edit-element-wrapper {
    z-index: 10001;
    position: relative;
}

/*
#dialog, .ui-front {
    z-index: 10002 !important;
}*/

CSS
        );

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
    
    //var jQueryElements = {".ml-hidden-edit-element":$(".ml-hidden-edit-element")};
    
    $(".ml-overlap-edit-element").click(function() {
        if (lastModifiedInfo) {
            return false;
        }
        
        $('<div id="overlay"><\/div>').appendTo(document.body);
        
        var elemOverlap = $(this);
        var elemEditWrapper = elemOverlap.next(".ml-hidden-edit-element-wrapper");
        elemOverlap.hide();
        elemEditWrapper.show();
        elemEdit = elemEditWrapper.find(".ml-hidden-edit-element");
        elemEdit.focus();
        
        lastModifiedInfo = {"elem-overlap":elemOverlap, "elem-edit-wrapper":elemEditWrapper, "elem-edit":elemEdit, "elem-edit-old-value":elemEdit.val()};
    });
    
    $(".ml-hidden-edit-element").change(function(e) {
        elementChanged($(this));
    }).blur(function() {
        updateElement($(this));
        cl('blur');
    }).keypress(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
            $(this).blur();
            return false;
        }
    }).keyup(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '27'){
            if (lastModifiedInfo && lastModifiedInfo["elem-edit-old-value"]) {
                $(this).val(lastModifiedInfo["elem-edit-old-value"]);
                elementChanged($(this));
                $(this).blur();
                return false;
            }
        }
    });
    
    templateElements["new"]["element-container"]["elem"] = $(templateElements["new"]["element-container"]["selector"]);
    
    $(templateElements["new"]["add-button"]["selector"]).click(function() {
        cl('add');
        var elemContainer = templateElements["new"]["element-container"]["elem"];
        if (elemContainer.data("newElementProcessing") != "in-process") {
            elemContainer.data("newElementProcessing", "in-process");
            var html = $(templateElements["new"]["template-container"]["selector"]).html();
            //elemContainer.append('<div class="ml-new-element-wrapper">' + html + '<\/div>');
            elemContainer.append(html);
            addNewElementProcessStart();
        } else {
            alert("Новый элемент уже в процессе создания.");
        }
    });
    
    $(templateElements["delete-button"]["selector"]).click(function() {
        cl('delete');
        if (lastModifiedInfo) {
            cl('delete discarded');
            return false;
        }
        var formElem = $(this).closest('form');
        deleteElement(formElem);
        return false;
    });
    
    function addNewElementProcessStart() {
        $(templateElements["new"]["save-cancel-button"]["selector"]).unbind().click(function() {
            //$(this).closest(".ml-new-element-wrapper").remove();
            $(this).closest("form").remove();
            templateElements["new"]["element-container"]["elem"].data("newElementProcessing", "no-process");
            return false;
        });
        
        //$(".ml-new-element-wrapper form").unbind().submit(function() {
        $("form.lm_form_0").unbind().submit(function() {
            var formElem = $(this);
            $.post(actions["create"], formElem.serialize(), function(data) {
                if (data) {
                    if (data.state == "success") {
                       alert('Элемент успешно добавлен.');
                       location.reload(true);
                    } else if (data.state == "error") {
                        showErrorMessages(data.data["error-messages"]);
                    }
                    correctAttributes(formElem, data);
                }
            }).fail(function(jqXHR, textStatus, error) {
                alert("Ошибка на сервере " + jqXHR.status + ": " + error);
            });
            
            return false;
        });
    }
    
    function correctAttributes(formElem, data) {
      if (data && data.data && data.data["corrected-attributes"]) {
            $.each(data.data["corrected-attributes"], function(id, val) {
                var correctElem = formElem.find('[name="' + id + '"]');
                correctElem.val(val);
                elementChanged(correctElem);
            });
        }
    }
    
    function resetLastModified() {
        if (lastModifiedInfo) {
            lastModifiedInfo["elem-edit-wrapper"].hide();
            lastModifiedInfo["elem-overlap"].show();
            //lastModifiedInfo["elem-edit"].blur();
            lastModifiedInfo = false;
        }
        $("#overlay").remove();
        $(".error-subtext").remove();
    }
    
    function updateElement(elem) {
        if (lastModifiedInfo) {
            if (lastModifiedInfo["elem-edit-old-value"] == elem.val()) {
                resetLastModified();
                return false;
            }
        }
        
        var formElem = elem.closest('form');
        $.post(actions["update"], formElem.serialize(), function(data) {
            if (data) {
                if (data.state == "success") {
                   resetLastModified();
                } else if (data.state == "error") {
                    showErrorMessagesSubtext(data.data["error-messages"], elem);
                    elem.focus();
                }
                correctAttributes(formElem, data);
            }
        }).fail(function(jqXHR, textStatus, error) {
            alert("Ошибка на сервере " + jqXHR.status + ": " + error);
        });
    }
    
    function showErrorMessages(messages) {
        var msg = Utils.assocArrayJoin(messages, "\\n");
        if (msg.length) {
            alert(msg);
        } else {
            alert('Неизвестная ошибка. Повторите пожалуйста позже и/или перезагрузите страницу.');
        }
    }
    
    function showErrorMessagesSubtext(messages, elem) {
        $(".error-subtext").remove();
        var errHtml = '<div class="error-subtext">';
        var msg = Utils.assocArrayJoin(messages, "<br>");
        if (msg.length) {
            errHtml += msg;
        } else {
            errHtml += 'Неизвестная ошибка.<br>Повторите пожалуйста позже и/или перезагрузите страницу.';
        }
        errHtml += '<\/div>';
        elem.after(errHtml);
    }
    
    function deleteElement(formElem) {
        formElem.css({"background-color":"red","color":"white"});
        setTimeout(function() {
            if (confirm("Действительно хотите удалить выделенный элемент?")) {
                //var id = formElem.find("[name='lm_form_id']").val();
                var data = formElem.serialize();
                //$.post(actions["delete"] + '?' + $.param({id:id}), function(data) {
                $.post(actions["delete"], data, function(data) {
                    if (data) {
                        if (data.state == "success") {
                           alert("Элемент успешно удалён.");
                           formElem.remove();
                           //lastModifiedInfo = false;
                        } else if (data.state == "error") {
                            showErrorMessages(data.data["error-messages"]);
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
        }, 0);
    }
    
    function elementChanged(currElem) {
        var currentValue = (currElem.prop("tagName") == 'SELECT') ? currElem.find("option:selected").text() : currElem.val();
        currElem.parent(".ml-hidden-edit-element-wrapper").prev(".ml-overlap-edit-element").text(currentValue);      
    }

})();
JS
        );
    }

    public function beginElement($id, $props = [])
    {
        $this->_currentForm = new Form();

        return $this->_currentForm->begin($id, $props);
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
