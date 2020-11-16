<?php

namespace applications\core;

use Exception;

class Controller
{
    protected $path = 'unknown';
    protected $view;

    public function __construct ()
    {
        $this->view = new View();
    }

    public function index()
    {
        if (class_exists($this->path)) {
            $data = (new $this->path($_REQUEST))->handler()->getResult();
        } else {
            throw new Exception('Экшн не найден', 404);
        }

        return $this->view->view($data['view'], $data['title'], $data['responseCode'], $data['data']);
    }
}