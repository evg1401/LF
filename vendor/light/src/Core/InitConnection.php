<?php


namespace Core;


use RedBeanPHP\R;

class InitConnection
{
    public static function initConnection()
    {
        $config = parse_ini_file(ROOT . '/config.ini');

        if ($config['DBtype'] === 'sqlite') {
            R::setup('sqlite:' . $config['SQLitePath']);
        } else {
            $dsn = $config['DBtype'] . ':host=' . $config['DBhost'] . ';' . 'dbname=' . $config['DBname'];

            R::setup($dsn, $config['DBuser'], $config['DBpassword'], $config['DBfreeze']);
        }
    }
}