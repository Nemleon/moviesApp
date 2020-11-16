<?php

namespace applications\core;

Class Route
{
    public static function start ()
    {
        $controllerName = 'Movies';
        $action = 'index';

        $explode = explode('?', trim(strip_tags(trim(urldecode($_SERVER['REQUEST_URI']), ' ../'))));

        $uri = $explode[0];
        $routes = explode('/', $uri);

        if (! empty($routes[0])) {
            $controllerName = $routes[0];
            unset($routes[0]);
        }

        if (! isset($routes[0])) {
            if (!empty($routes)) {
                $action = implode('\\', $routes);
            }
        }

        $controllerPath = 'applications\\controllers\\' . ucfirst($controllerName) . 'Controller';

        if(class_exists($controllerPath)) {
            $controller = new $controllerPath($action);
            echo $controller->index();
            exit();
        } else {
            throw new \Exception('Класс не найден', 404);
        }
    }
}
