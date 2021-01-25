<?php


namespace Core;


abstract class Controller
{
    protected array $route;
    public object $view;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = new View($route);
    }
}