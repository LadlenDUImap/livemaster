<?php

namespace app\base;

use app\core\View;

/**
 * Controller - базовый класс для контроллеров.
 *
 * @package base
 */
abstract class Controller
{
    /** @var string макет используемый при генерации, можно переопределить в производном классе. */
    protected $layout = 'main.php';

    /** @var View используется для генерации кода */
    protected $view;


    /**
     * Действие по умолчанию.
     */
    //abstract function actionIndex();

    public function __construct()
    {
        $this->view = new View;
    }

    public function getView()
    {
        return $this->view;
    }

    /**
     * Генерация содержимого документа и вызов генерации всего документа.
     *
     * @param string $viewName Вид для генерации.
     * @param array $params Параметры.
     * @return string Сгенерированный документ.
     */
    protected function render(string $viewName, array $params = null)
    {
        $file = APP_DIR . 'views/content/' . $viewName . '.php';
        $content = $this->view->render($file, $params);
        return $this->renderLayout($content);
    }

    /**
     * Генерация всего документа.
     *
     * @param string $content Основное содержимое документа, подставляемое в шаблон.
     * @return string Сгенерированный документ.
     */
    protected function renderLayout($content)
    {
        $file = APP_DIR . 'views/layouts/' . $this->layout;
        return $this->view->renderLayout($file, $content);
    }
}
