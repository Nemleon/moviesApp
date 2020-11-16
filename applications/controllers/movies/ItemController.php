<?php

namespace applications\controllers\movies;

use applications\controllers\Interfaces\AbstractGeneralController;
use applications\models\DB\Models\Model;

class ItemController extends AbstractGeneralController
{
    private $request;

    public function __construct($request = null)
    {
        if (isset($request['name'])) {
            $this->title = mb_convert_case($request['name'], MB_CASE_TITLE, 'UTF-8');
            $this->request = $request;
        } else {
            $this->title = 'Фильма нема';
            $this->errors = 'Фильм не выбран!';
        }

        $this->view = 'item_view.php';
    }

    public function handler()
    {
        if (! $this->errors) {
            try {
                $data = (new Model())
                    ->select('movies', "movies.*, GROUP_CONCAT(actors.name SEPARATOR ', ') as actors")
                    ->join('actor_movie', 'movies.id', '=', 'actor_movie.movie_id')
                    ->join('actors', 'actors.id', '=', 'actor_movie.actor_id')
                    ->where('movies.name', '=', $this->request)
                    ->first();

                if ($data->name) {
                    $this->data['film'] = $data;
                    $this->responseCode = 200;
                } else {
                    $this->responseCode = 404;
                    $this->data['errors'] = 'Такой фильм не найден';
                }
            } catch (\Exception $e) {
                $this->responseCode = $e->getCode();
                $this->data['errors'] = 'Произошла ошибка на сервере. Пожалуйста, попробуйте позже';
            }
        } else {
            $this->data['errors'] = $this->errors;
            $this->responseCode = 400;
        }

        return $this;
    }
}