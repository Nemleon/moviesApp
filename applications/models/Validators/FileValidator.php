<?php

namespace applications\models\Validators;

class FileValidator
{
    static public function validator($file)
    {
        $errors = false;

        if  ($file) {
            $path = 'others/downloads';

            if ($file['text']['error'] === 0) {
                $tmpName = $file["text"]["tmp_name"];
                $name = strip_tags(basename($file["text"]["name"]));
                move_uploaded_file($tmpName, "{$path}/{$name}");

                $result = "{$path}/{$name}";
            } else {
                $result = 'При загрузке файла произошла ошибка. Пожалуйста, попробуйуте еще раз!';
                $errors = true;
            }

        } else {
            $result = 'Файл небыл загружен';
            $errors = true;
        }

        return ['errors' => $errors, 'data' => $result];
    }
}