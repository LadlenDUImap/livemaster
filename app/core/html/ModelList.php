<?php

namespace app\core\html;

use app\base\DatabaseRecord;
use app\core\Lm;
use app\helpers\ClassHelper;

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


    public function __construct($templateElements = [])
    {
        $this->templateElements = array_replace_recursive($this->templateElements, $templateElements);
    }

    public function textInput($name = '', $params = [])
    {
        $nameHtml = $this->partHtmlName($name);
        $paramsHtml = $this->partHtmlParams($params);

        $valueHtml = '';
        if ($this->model && $name) {
            $valueHtml = ' value="' . Safe::htmlEncode($this->model->$name) . '" ';
        }

        return '<input type="text"' . $nameHtml . $valueHtml . $paramsHtml . ' />';
    }

    public function selectInput($name = '', $options = [], $selectedValue = false, $params = [])
    {
        $nameHtml = $this->partHtmlName($name);
        $paramsHtml = $this->partHtmlParams($params);

        $selectedName = '';

        $html = '<div style="display:none;"><select' . $nameHtml . $paramsHtml . '>';

        foreach ($options as $opt) {
            $html .= '<option';
            if (!empty($opt['value'])) {
                if ($selected = ($selectedValue == $opt['value']) ? ' selected="selected" ' : '') {
                    $selectedName = $opt['name'];
                }
                $html .= ' value="' . Safe::htmlEncode($opt['value']) . '"' . $selected;
            } else {
                $selectedName = $opt['name'];
            }
            $html .= '>' . Safe::htmlEncode($opt['name']) . '</option>';
        }

        $html .= '</select></div>';

        $html = '<div' . $paramsHtml . '>' . Safe::htmlEncode($selectedName) . '</div>' . $html;

        return $html;
    }
}
