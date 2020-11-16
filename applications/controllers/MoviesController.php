<?php

namespace applications\controllers;

use applications\core\Controller;

Class MoviesController extends Controller
{
    public function __construct($actionName)
    {
        parent::__construct();
        $this->path = __NAMESPACE__ . "\\movies\\" . ucfirst($actionName) . "Controller";

    }
}