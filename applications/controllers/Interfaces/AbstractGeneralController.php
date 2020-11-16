<?php

namespace applications\controllers\interfaces;

abstract class AbstractGeneralController
{
    protected $data = [];
    protected $title;
    protected $view;
    protected $errors = false;
    protected $responseCode;

    abstract public function handler();

    public function getResult()
    {
        return ['data' => $this->data, 'title' => $this->title, 'view' => $this->view, 'responseCode' => $this->responseCode];
    }
}