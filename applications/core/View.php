<?php

namespace applications\core;

class View
{
    private $templateName;

    public function __construct($templateName = 'template_view.php')
    {
        $this->templateName = $templateName;
    }

    public function view($contentView, $title, $responseCode, $data = [])
    {
        http_response_code($responseCode);
        return $this->renderTemplate($this->renderPage($contentView, $data), $title);
    }

    private function renderTemplate($contentView, $title)
    {
        $layoutView = "applications\\views\\templates\\{$this->templateName}";

        if (file_exists($layoutView)) {
            ob_start();
            //$contentView и $title используются в подключаемом темплейте
            include $layoutView;

            return ob_get_clean();
        } else {
            throw new \Exception('Шаблон не найден', 404);
        }
    }

    private function renderPage($viewName, $data)
    {
        $view = "applications\\views\\{$viewName}";

        if (file_exists($view)) {
            ob_start();
            extract($data);
            //Переменные, полученные из массива $data используются в подключаемой вьюхе
            //Названиями переменных являются ключи массива
            include $view;

            return ob_get_clean();
        } else {
            throw new \Exception('Вьюха не найдена', 404);
        }
    }
}
