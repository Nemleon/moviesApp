<?php

namespace applications\controllers\movies;

use applications\controllers\interfaces\AbstractGeneralController;
use applications\models\DB\Models\Model;
use applications\models\Validators\FileValidator;
use applications\models\Validators\Validator;

class AddMoviesFromFileController extends AbstractGeneralController
{
    private $shittedSymbols = 'п»ї'; //Непредвиденные символы, появляющиеся из ниоткуда. Переменная для trim() чтобы удалить их

    private $moviesToAdd;
    private $actorsToAdd;

    private $toRelationships;

    private $forCompleteMessage;

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new \Exception('Недоступный метод, используйте Post', 403);
        }

        $filePath = FileValidator::validator($_FILES);

        if (! $filePath['errors']) {
            $file = file($filePath['data']);
            unlink($filePath['data']);
        } else {
            $this->errors = $filePath['data'];
            return;
        }

        $file = mb_convert_encoding($file, 'utf-8', 'cp1251');

        $editedFile = $this->fileToArray($file);
        $items = $this->editArrayItems($editedFile);

        if (! $items) {
            $this->errors = 'Шаблон полностью не валиден. Перечитайте инструкцию, перепроверьте и попробуйте снова';
            return;
        }

        $actorsNames = $this->getActorsNames($items);
        $moviesNames = $this->getMoviesNamesForValidate($items);

        $validMoviesNames = Validator::unique('movies', 'name', $moviesNames);
        $validActorsNames = Validator::unique('actors', 'name', $actorsNames);

        if ($validMoviesNames !== null) {
            $params = $this->setMoviesToAddAndRelationships($items, $validMoviesNames['exists']);

            $this->moviesToAdd = $params['toAdd'];
            $this->toRelationships = $params['toRelationships'];
            ($validActorsNames !== null)
                ? $this->actorsToAdd['name'] = $validActorsNames['unique']
                : $this->actorsToAdd = false;

            $this->forCompleteMessage = $validMoviesNames;

        } else {
            $this->errors = 'Все предлагаемые фильмы уже существуют в базе!';
        }
    }

    public function handler()
    {
        if (! $this->errors) {

            try {
                $DB = new Model();
                $DB->insert('movies', $this->moviesToAdd)->save();

                if ($this->actorsToAdd) {
                    $DB->insert('actors', $this->actorsToAdd)->save();
                }

                $startRequest = "INSERT INTO actor_movie (actor_id, movie_id) VALUES ";
                $request = '';
                $params = [];
                $delimiter = 1000;

                for ($i = 0; $i < count($this->toRelationships['name']); $i++) {
                    $request .= $startRequest;
                    $movieName = $this->toRelationships['name'][$i];
                    $params['movieName'.$i] = $movieName;

                    for ($k = 0; $k < count($this->toRelationships['actors'][$i]); $k++) {
                        $request .= "((SELECT id FROM actors WHERE name = :actor{$i}{$delimiter}{$k}), (SELECT id FROM movies WHERE name = :movieName{$i})),";
                        $params['actor'.$i.$delimiter.$k] = $this->toRelationships['actors'][$i][$k];
                    }

                    $request = trim($request, ' ,');
                    $request .= '; ';
                }

                $DB->customRequest($request)->setParams($params)->execute();

                $this->responseCode = 201;
                $this->data['message'] = $this->setCompleteMessage();
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

    private function setCompleteMessage()
    {
        $addedMovies = "";
        $disallowMovies = "";

        foreach ($this->forCompleteMessage as $key => $value) {

            switch ($key) {
                case 'exists':
                    if ($value) {
                        $disallowMovies = $value;
                    } else {
                        $disallowMovies = "";
                    }
                    break;

                case 'unique':
                    $addedMovies = $value;
                    break;
            }
        }

        return ['added' => $addedMovies, 'disallow' => $disallowMovies];
    }

    public function getResult()
    {
        http_response_code($this->responseCode);
        $result = json_encode($this->data, JSON_UNESCAPED_UNICODE);
        print_r($result);
        exit;
    }

    private function setMoviesToAddAndRelationships($movies, $validatedItems)
    {
        $numOfMovies = count($movies);
        $toAdd = [];
        $toRelationships = [];

        if ($validatedItems) {
            for ($i = 0; $i < $numOfMovies; $i++) {
                foreach($movies[$i] as $key => $value) {
                    foreach ($validatedItems as $movieName) {
                        if ($key === 'name') {
                            if ($value === $movieName) {
                                unset($movies[$i]);
                            }
                        }
                    }
                }
            }
        }

        sort($movies);

        for ($i = 0; $i < count($movies); $i++) {
            foreach($movies[$i] as $key => $value) {
                switch ($key) {
                    case 'name':
                        $toAdd[$key][] = $value;
                        $toRelationships[$key][] = $value;
                        break;

                    case 'actors':
                        $toRelationships[$key][] = $value;
                        break;

                    default:
                        $toAdd[$key][] = $value;
                        break;
                }
            }
        }

        return ['toAdd' => $toAdd, 'toRelationships' => $toRelationships];
    }

    private function fileToArray($file)
    {
        $editFile = [];
        $previousStr = "";
        $counter = - 1;

        foreach ($file as $item) {
            $editValue = trim(strip_tags($item));

            if ($previousStr === "" && $editValue !== "") {
                ++$counter;
            } elseif ($previousStr === "" && $editValue === "") {
                continue;
            }

            if ($editValue !== '') {
                $prepare = explode(':', $editValue);
                foreach ($prepare as $prepKey => $prepValue) {
                    $prepare[$prepKey] = trim(trim($prepValue, $this->shittedSymbols));
                }

                $key = $prepare[0];
                unset($prepare[0]);

                $editFile[$counter][trim(trim($key), $this->shittedSymbols)] = trim(implode(': ', $prepare));
            }

            $previousStr = $editValue;
        }

        return $editFile;
    }

    private function getActorsNames($array)
    {
        $actors = [];

        for ($i = 0; $i < count($array); $i++) {
            foreach ($array[$i] as $key => $value) {
                if (strtolower($key) === 'actors') {
                    $reverse = array_flip($array[$i][$key]);
                    $actors = array_merge($actors, $reverse);
                }
            }
        }

        $counter = -1;
        foreach ($actors as $key => $value) {
            ++$counter;
            $actors[$key] = $counter;
        }

        $actors = array_flip($actors);

        return $actors;
    }

    private function editArrayItems($array)
    {
        $numOfItems = count($array);

        for ($i = 0; $i < $numOfItems; $i++) {
            if (!isset($array[$i]['Title']) || !isset($array[$i]['Stars']) || !isset($array[$i]['Format']) || !isset($array[$i]['Release Year'])) {

                unset($array[$i]);

            } else {

                foreach ($array[$i] as $key => $value) {

                    switch(strtolower($key)) {
                        case 'title':
                            $array[$i]['name'] = $value;
                            break;

                        case 'release year':
                            $array[$i]['release_year'] = $value;
                            break;

                        case 'format':
                            $array[$i]['format'] = $value;
                            break;

                        case 'stars' :
                            $array[$i]['actors'] = explode(',', $value);
                            foreach ($array[$i]['actors'] as $actorKey => $actor) {
                                $array[$i]['actors'][$actorKey] = trim(trim($actor), $this->shittedSymbols) ;
                            }
                            break;
                    }

                    unset($array[$i][$key]);

                }

            }
        }

        sort($array);

        return $array;
    }

    private function getMoviesNamesForValidate($movies)
    {
        $moviesNames = [];

        for ($i = 0; $i < count($movies); $i++) {
            foreach ($movies[$i] as $key => $value) {
                if (strtolower($key) === 'name') {
                    $moviesNames[] = $value;
                }
            }
        }

        return $moviesNames;
    }
}