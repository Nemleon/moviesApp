<?php

namespace applications\models\Validators;

class SearchRequestValidator
{
    static public function validator($request)
    {
        $error = false;
        $result = [];

        if (isset($request['name']) && isset($request['type'])) {
            $name = trim(strip_tags($request['name'][0]), " '.,\"");
            $type = trim(strip_tags($request['type']), " '.,\"");

            if ($name !== '' && $type !== '') {
                
                if ($type === 'movies' || $type === 'actors') {
                    $result['param'] = "%{$name}%";
                    $result['type'] =  $type;
                } else {
                    $error = true;
                    $result = 'Используйте поиск в актерах или фильмах';
                }

            } else {
                $error = true;
                $result = 'Параметр(ы) поиска не задан(ы)';
            }

        } else {
            $error = true;
            $result = 'Параметр(ы) поиска не задан(ы)';
        }

        return ['errors' => $error, 'data' => $result];
    }
}