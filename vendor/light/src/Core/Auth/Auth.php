<?php

namespace Core\Auth;

use Core\InitConnection;
use RedBeanPHP\R;

/**
 * Class Auth
 * @package Core\Auth
 */
abstract class Auth
{

    /**
     * @var array
     */
    protected array $db = [
        'table' => 'auth',
        'columns' => [
            'username' => 'username',
            'password' => 'password',
            'email' => 'email',
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'remember_token' => 'remember_token']
    ];

    /**
     * @var array
     */
    protected array $data = [];
    /**
     * @var int
     */
    protected $cookieExpires = 86400; //время истечения cookie
    /**
     * @var string
     */
    protected $logoutRedirect = 'login';
    protected $loginRedirect = 'home';

    /**
     * @param $data
     * @return array
     */
    protected function checkEmptyFieldsRegistration($data)
    {
        if (!empty($data)) {
            if ($data['username'] === '') {
                $errors[] = 'заполните поле логин';
            }

            if ($data['password'] === '') {
                $errors[] = 'заполните поле пароль';
            }
            if ($data['password_confirm'] === '') {
                $errors[] = 'заполните повторный ввод пароля';
            }
            if ($data['password'] !== $data['password_confirm']) {
                $errors[] = 'пароли не совпадают';
            }

            if ($data['email'] === '') {
                $errors[] = 'заполните поле E-mail';
            }

            if ($data['first_name'] === '') {
                $errors[] = 'заполните поле имя';
            }

            if ($data['last_name'] === '') {
                $errors[] = 'заполните поле Фамилия';
            }

            if (isset($errors)) {
                return $errors;
            }
        }
    }

    /**
     * @param $data
     * @return bool
     */
    protected function checkEmptyFieldsLogin($data)
    {
        if ($data['username'] !== '' && $data['password'] !== '') {

            return true;

        }
    }


    /**
     * @param $data
     * @return bool
     */
    protected
    function checkColumnMatch($data)
    {
        if (!R::findOne($this->db['table'], 'username = ?', [$data['username']]) &&
            !R::findOne($this->db['table'], 'email = ?', [$data['email']])) {
            return true;
        }
    }
}