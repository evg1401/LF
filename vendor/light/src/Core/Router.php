<?php

namespace Core;

use Core\Auth\checkAuth;

/**
 * Class Router
 * @package Core
 */
class Router
{

    /**
     * @var array|mixed
     */
    protected array $routes;
    /**
     * @var string
     */
    protected string $uri;
    /**
     * @var array
     */
    protected array $internalRouter;
    /**
     * @var string
     */
    protected string $tmpParameters;
    /**
     * @var array
     */
    protected array $parameters;

    public function __construct()
    {
        $this->routes = require(ROOT . '/Routes/routes.php');
        $this->uri = trim($_SERVER['REQUEST_URI'], '/');
    }


    /**
     * @return bool
     */
    public function matches()
    {
        foreach ($this->routes as $route => $parameters) {
            if (preg_match("#^" . $route . "(\/[A-Za-z0-9]+){0,}?$#", $this->uri, $matches)) {
                $this->route = $route;
                $this->internalRouter = $parameters;
            }
        }

        if (isset($this->route)) {
            $this->explode = explode($this->route . '/', $this->uri);
            $this->regex = preg_grep("/[\w]/", $this->explode);

            foreach ($this->regex as $key => $value) {
                $this->tmpParameters = $value;
            }

            $this->parameters = explode('/', $this->tmpParameters);
            
            return true;
        }
    }

    public function run(): void
    {
        if ($this->matches()) {
            $this->internalRouter['controller'] = str_replace('/', '\\', $this->internalRouter['controller']); //поиск  в контроллере "/"
            //и замена на "\", корректный для полного названия класса
            $controllerName = '\App\Controllers\\' . $this->internalRouter['controller'];
            $action = $this->internalRouter['action'];

            if (in_array('Auth', $this->internalRouter)) {
                $checkAuth = new checkAuth();
                if (!$checkAuth->check()) {

                    View::redirect('login');
                }
            }

            if (class_exists($controllerName)) {

                $controllerObject = new $controllerName();

                if (method_exists($controllerObject, $action)) {

                    if (!empty($this->parameters)) {

                        call_user_func_array([$controllerObject, $action], $this->parameters);
                    } else {
                        $controllerObject->$action();
                    }
                } else {
                    View::methodError();
                }

            } else {
                View::controllerError();
            }

        } else {
            View::HttpResponse(404);
        }
    }
}