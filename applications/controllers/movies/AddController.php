<?php

namespace applications\controllers\movies;

use applications\controllers\interfaces\AbstractGeneralController;

class AddController extends AbstractGeneralController
{
    public function __construct()
    {
        $this->title = 'Добавить фильм';
        $this->view = 'add_movie.php';
        $this->responseCode = 200;
    }

    public function handler()
    {
        return $this;
    }
}