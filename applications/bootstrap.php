<?php

use applications\controllers\ErrorController;
use applications\core\Route;

spl_autoload_register(function ($class)
{
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $file = $class . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        throw new Exception('Файл не найден', 404);
    }
});

try {
    Route::start();
} catch (Exception $e) {
    echo ErrorController::callErrorView($e);
    exit;
}


