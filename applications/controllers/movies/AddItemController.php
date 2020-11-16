<?php

namespace applications\controllers\movies;

use applications\controllers\interfaces\AbstractGeneralController;
use applications\models\DB\Models\Model;
use applications\models\Validators\Validator;

class AddItemController extends AbstractGeneralController
{
    private $actorsToAdd;
    private $actorsToRelations;
    private $movie;

    public function __construct($request = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception('Недоступный метод, используйте Post', 403);
        }

        $request = $request['data'];

        if (isset($request['actors']['name']) && isset($request['movies']['name'])) {

            if (($request['actors']['name'] === [] || $request['actors']['name'][0] == '')
            || ($request['movies']['name'] === [] || $request['movies']['name'][0] == '')) {

                $this->errors = "Вы не заполнили поле(я) 'Актеры'/'Название фильма'!";
                return;

            } else {

                $actorsToArr = $this->transStrToArray($request['actors']['name'][0]);
                
            }
        } else {
            $this->errors = "Вы не заполнили поле(я) 'Актеры'/'Название фильма'!";
            return;
        }

        $movies = Validator::unique('movies', 'name', $request['movies']['name']);
        $actors = Validator::unique('actors', 'name', $actorsToArr);

        if ($movies !== null) {

            $this->movie = $request['movies'];
            $this->movie['name'] = $movies['unique'];

            $this->actorsToRelations['name'] = $actorsToArr;
            ($actors !== null)
                ? $this->actorsToAdd['name'] = $actors['unique']
                : $this->actorsToAdd = false;

        } else {
            $this->errors = 'Предлагаемый фильм уже существует в базе!';
        }
    }

    public function handler()
    {
        if (! $this->errors) {

            try {
                $DB = new Model();
                $DB->insert('movies', $this->movie)->save();

                if ($this->actorsToAdd) {
                    $DB->insert('actors', $this->actorsToAdd)->save();
                }

                $newRelations = "INSERT INTO actor_movie (actor_id, movie_id) VALUES ";
                $requestParams['movieName'] = $this->movie['name'][0];

                for ($i = 0; $i < count($this->actorsToRelations[key($this->actorsToRelations)]); $i++ ) {
                    $newRelations .= "((SELECT id FROM actors WHERE name = :actor{$i}), (SELECT id FROM movies WHERE name = :movieName)),";
                    $requestParams["actor".$i] = $this->actorsToRelations[key($this->actorsToRelations)][$i];
                }

                $newRelations = trim($newRelations, " ,");

                $DB->customRequest($newRelations)->setParams($requestParams)->execute();

                $this->responseCode = 201;
                $this->data['message'] = 'Фильм был успешно добавлен!';
            } catch (\Exception $e) {
                $this->responseCode = $e->getCode();
                $this->data['errors'] = 'Произошла ошибка на сервере. Пожалуйста, попробуйте позже';
            }

        } else {
            $this->responseCode = 400;
            $this->data['errors'] = $this->errors;
        }

        return $this;
    }

    private function transStrToArray($string)
    {
        $actors = explode(',', $string);
        for ($i = 0; $i < count($actors); $i++) {
            $actors[$i] = trim(strip_tags($actors[$i]));
        }

        return $actors;
    }

    public function getResult()
    {
        http_response_code($this->responseCode);
        $result = json_encode($this->data, JSON_UNESCAPED_UNICODE);
        print_r($result);
        exit;
    }
}