<?php

namespace applications\models\Validators;

use applications\models\DB\Models\Model;

class Validator
{
    static function unique($table, $column, $items)
    {
        $response = [];
        $response['exists'] = [];

        $result = (new Model())
            ->select($table, $column)
            ->where($column, 'in', $items)
            ->get();

        foreach ($items as $key => $value) {
            $value = trim(strip_tags($value));
            foreach ($result as $movie) {
                if ($value === $movie->name) {
                    $response['exists'][] = $movie->name;
                    unset($items[$key]);
                }
            }
        }

        sort($items);

        ($items !== [])
            ? $response['unique'] = $items
            : $response = null;

        return $response;
    }

}