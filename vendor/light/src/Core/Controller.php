<?php


namespace Core;


/**
 * Class Controller
 * @package Core
 */
abstract class Controller
{
    /**
     * @param $view
     * @param array|null $param
     * @return View
     */
    public function render($view, array $param = null): View
    {
        return new View($view, $param);
    }
}