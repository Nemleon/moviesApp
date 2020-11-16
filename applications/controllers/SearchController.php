<?php

namespace applications\controllers;

use applications\core\Controller;

class SearchController extends Controller
{
    public function __construct($actionName)
    {
        parent::__construct();
        $this->path = __NAMESPACE__ . "\\search\\" . ucfirst($actionName) . "Controller";
    }
}