<?php

namespace Core;

use Twig\Environment;

/**
 * Class View
 * @package Core
 */
class View
{

    /**
     * View constructor.
     * @param string $view
     * @param array $param
     */
    public function __construct(protected string $view, array $param)
    {
        $loader = new \Twig\Loader\FilesystemLoader('App\Views');
        $this->twig = new Environment($loader);
        $this->render($param);
    }

    /**
     * @param $param
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render($param)
    {
        if (!file_exists('App/Views/' . $this->view)) {

            View::HttpResponse(404);
        }

        $render = $param !== null ? $this->twig->render($this->view, $param) : $this->twig->render($this->view);
        echo $render;
    }


    public static function controllerError()
    {
        echo 'Ошибка: в файле ' . ROOT . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'routes.php указан не существующий контроллер.';
    }


    public static function methodError()
    {
        echo 'Ошибка: в файле ' . ROOT . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'routes.php указан не существующий метод контроллера.';
    }

    /**
     * @param $code
     */
    public static function HttpResponse($code): void
    {
        http_response_code($code);
        require ROOT . '/App/Views/Errors/' . $code . '.php';
        exit();
    }

    public static function redirect($url): void
    {
        header("Location: /" . $url);
        exit();
    }
}