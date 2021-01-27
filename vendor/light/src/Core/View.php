<?php

namespace Core;

use Twig\Environment;

class View
{
    public array $route;
    public Environment $twig;

    public function __construct($route)
    {
        $this->route = $route;
        $loader = new \Twig\Loader\FilesystemLoader('App\Views');
        $this->twig = new Environment($loader);
    }

    public function require($view, $param = null): void
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

    public function render($view, $var = null)
    {
        if (!file_exists('App/Views/' . $view)) {

            View::HttpResponse(404);
        }

        $render = $var !== null ? $this->twig->render($view, $var) : $this->twig->render($view);
        echo $render;
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