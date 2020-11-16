<?php

namespace applications\controllers\movies;

use applications\controllers\Interfaces\AbstractGeneralController;
use applications\models\DB\Models\Model;

class IndexController extends AbstractGeneralController
{
    public function __construct($request = null)
    {
        $this->title = 'Все фильмы';
        $this->view = 'main_view.php';
    }

    public function handler()
    {
        try {
            $this->data['films'] = (new Model())
                ->select('movies', '*')
                ->orderBy('name', 'ASC')
                ->get();
        } catch (\Exception $e) {
            $this->responseCode = $e->getCode();
            $this->data['errors'] = 'Произошла ошибка на сервере. Пожалуйста, попробуйте позже';
        }

        return $this;
    }
}