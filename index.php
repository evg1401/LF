<?php

use Core\InitConnection;
use Core\Router;
use RedBeanPHP\R;

define('ROOT', __DIR__);
require_once ROOT . '/vendor/autoload.php';

if (file_exists(ROOT . '/config.ini')) {
    InitConnection::initConnection(); //установка соединения с БД
    if (!R::testConnection()) die('Нет соединения с БД! Проверьте корректность настроек соединения с БД в конфигурационном файле ' . ROOT . DIRECTORY_SEPARATOR . 'config.ini');
}

$router = new Router();
$router->run();
