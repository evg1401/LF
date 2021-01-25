<?php

use Core\Router;

define('ROOT', __DIR__);
require_once ROOT . '/vendor/autoload.php';


$router = new Router();
$router->run();
