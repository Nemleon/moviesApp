<?php

use applications\controllers\ErrorController;
use applications\core\Route;

spl_autoload_register(function ($class)
{
    $file = strtolower($class) . '.php';

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


