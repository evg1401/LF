<?php


namespace App\Models;

use Core\InitConnection;
use RedBeanPHP\R;
use RedBeanPHP\SimpleModel;

class News extends SimpleModel
{
    public function __construct()
    {
        InitConnection::initConnection(); //установка соединения с БД
        if (!R::testConnection()) die('No DB connection!');
    }

    public function writeNews($text)
    {

    }

    public function readNews($id)
    {

    }

    public function create()
    {

    }
}