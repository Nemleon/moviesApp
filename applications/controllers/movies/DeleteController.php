<?php

namespace applications\controllers\movies;

use applications\controllers\Interfaces\AbstractGeneralController;
use applications\models\DB\Models\Model;

class DeleteController extends AbstractGeneralController
{
    private $params = [];
    public function __construct($request = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception('Недоступный метод, используйте Post', 403);
        }

        if (isset($request['name'])) {
            $param = trim(strip_tags($request['name']));
            if ($param) {
                $this->params['name'] = $param;
            } else {
                $this->errors = 'Введен не валидный параметр';
            }
        } else {
            $this->errors = 'Параметр небыл введен';
        }
    }

    public function handler()
    {
        if (! $this->errors) {
            try {
                $result = (new Model())
                    ->delete('movies')
                    ->where('name', '=', $this->params)
                    ->destroy();

                if ($result) {
                    $this->responseCode = 200;
                    $this->data['deleteSuccess'] = 'Фильм "'.$this->params['name'].'" успешно удалён';
                } else {
                    $this->responseCode = 400;
                    $this->data['deleteErrors'] = 'Этого фильма нет в базе';
                }

            } catch (\Exception $e) {
                $this->responseCode = $e->getCode();
                $this->data['deleteErrors'] = 'Произошла ошибка на сервере. Пожалуйста, попробуйте позже';
            }

        } else {
            $this->responseCode = 400;
            $this->data['deleteErrors'] = $this->errors;
        }

        return $this;
    }

    public function getResult()
    {
        http_response_code($this->responseCode);
        $result = json_encode($this->data, JSON_UNESCAPED_UNICODE);
        print_r($result);
        exit;
    }
}