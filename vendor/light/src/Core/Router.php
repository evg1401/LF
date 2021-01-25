<?php

namespace Core;

class Router
{
    protected array $routes;
    protected string $uri;
    protected array $internalRouter;
    protected string $tmpParameters;
    protected array $parameters;

    public function __construct()
    {
        $this->routes = require(ROOT . '/Routes/routes.php');
        $this->uri = trim($_SERVER['REQUEST_URI'], '/');


    }

    public function matches(): bool
    {
        foreach ($this->routes as $route => $parameters) {
            if (preg_match("#^" . $route . "#", $this->uri, $matches)) {
                $this->tmp = explode('/', $this->uri);

                if ($matches[0] === $this->tmp[0]) {
                    $this->route = $route;
                    $this->internalRouter = $parameters;
                } else {
                    View::HttpResponse(404);
                }
            }
        }
        $this->explode = explode($this->route . '/', $this->uri);
        $this->regex = preg_grep("/[\w]/", $this->explode);
        foreach ($this->regex as $key => $value) {
            $this->tmpParameters = $value;
        }

        $this->parameters = explode('/', $this->tmpParameters);
        return true;
    }

    public function run(): void
    {
        if ($this->matches() && isset($this->internalRouter)) {
            $this->internalRouter['controller'] = str_replace('/', '\\', $this->internalRouter['controller']); //поиск  в контроллере "/"
            //и замена на "\", корректный для полного названия класса
            $controllerName = '\App\Controllers\\' . $this->internalRouter['controller'];
            $action = $this->internalRouter['action'];
        } else {
            View::HttpResponse(404);
        }

        if (class_exists($controllerName)) {

            $controllerObject = new $controllerName($this->internalRouter);

            if (method_exists($controllerObject, $action)) {

                if (!empty($this->parameters)) {

                    call_user_func_array([$controllerObject, $action], $this->parameters);
                } else {
                    $controllerObject->$action();
                }
            } else {
                View::HttpResponse(404);
            }

        } else {
            View::HttpResponse(404);
        }
    }


}