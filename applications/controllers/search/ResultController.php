<?php

namespace applications\controllers\search;

use applications\controllers\Interfaces\AbstractGeneralController;
use applications\models\DB\Models\Model;
use applications\models\Validators\SearchRequestValidator;

class ResultController extends AbstractGeneralController
{
    private $param = [];

    public function __construct($request = null)
    {

        $this->view = 'search_result.php';
        $this->title = 'Результаты поиска';

        $validRequest = SearchRequestValidator::validator($request);

        if (! $validRequest['errors']) {
            $this->param['param']['name'] = $validRequest['data']['param'];
            $this->param['table'] = $validRequest['data']['type'];
        } else {
            $this->errors = $validRequest['data'];
        }
    }

    public function handler()
    {
        if (! $this->errors) {
            try {
                $data = (new Model())
                    ->select('movies', "DISTINCT movies.*")
                    ->join('actor_movie', 'movies.id', '=', 'actor_movie.movie_id')
                    ->join('actors', 'actors.id', '=', 'actor_movie.actor_id')
                    ->where("{$this->param['table']}.name", 'like', $this->param['param'])
                    ->orderBy('name', 'ASC')
                    ->get();

                if ($data) {
                    $this->data['movies'] = $data;
                    $this->responseCode = 200;
                } else {
                    $this->responseCode = 404;
                    $this->data['errors'] = 'До данному запросу фильмы не найдены';
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