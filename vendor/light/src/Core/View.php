<?php

namespace Core;

class View
{
    public array $route;

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function render($view, $param = null): void
    {
        if (is_array($param)) {

            extract($param, EXTR_OVERWRITE);

        } else {
            $param = null;
        }

        if (file_exists('App/Views/' . $view)) {
            require 'App/Views/' . $view;
        } else {
            self::HttpResponse(404);
        }
    }

    public static function HttpResponse($code): void
    {
        http_response_code($code);
        require ROOT . '/App/Views/Errors/' . $code . '.php';
        exit();
    }

    public function redirect($url): void
    {
        header("Location: $url");
        exit();
    }
}