<?php

namespace applications\controllers;

use applications\core\View;

class ErrorController
{
    static public function callErrorView($data)
    {
        $view = new View('error_template.php');
        return $view->view('error_view.php', "Ошибка {$data->getCode()}", $data->getCode(), ['errData' => $data->getCode()]); /*, ['errData' => $data->getMessage()]*/
    }
}