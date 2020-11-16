<?php

namespace applications\controllers\search;

use applications\controllers\Interfaces\AbstractGeneralController;

class IndexController extends AbstractGeneralController
{
    public function __construct()
    {
        $this->title = 'Поиск';
        $this->view = 'search_movie.php';
        $this->responseCode = 200;
    }

    public function handler()
    {
        return $this;
    }
}