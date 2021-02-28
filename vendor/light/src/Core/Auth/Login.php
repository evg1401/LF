<?php


namespace Core\Auth;

use Core\Http\Request;
use Core\Router;
use Core\View;
use RedBeanPHP\R;

/**
 * Class Login
 * @package Core\Auth
 */
class Login extends Auth
{
    /**
     * @param $username
     * @param $password
     * @return bool|string
     */
    public function login($username, $password, $rememberMe)
    {
        $this->data['username'] = $username;
        $this->data['password'] = $password;

        if ($this->checkEmptyFieldsLogin($this->data)) {

            $find = R::find($this->db['table'], 'username = ?', [$this->data['username']]);

            if ($find[1]['username'] === $this->data['username'] && password_verify($this->data['password'], $find[1]['password'])) {

                setcookie('username', $find[1]['username']);

                if ($rememberMe === 'yes') {
                    if ($find[1]['remember_token'] !== '') {

                        setcookie('rememberToken', $find[1]['remember_token'], time() + $this->cookieExpires);

                    } else {

                        $rememberToken = bin2hex(random_bytes(16));
                        setcookie('rememberToken', $rememberToken, time() + $this->cookieExpires);
                        $addRememberToken = R::load($this->db['table'], $find[1]['id']);
                        $addRememberToken->remember_token = $rememberToken;
                        R::store($addRememberToken);

                    }
                }

                return View::redirect($this->loginRedirect);

            } else {

                return 'Неверно введены пара логин/пароль';

            }
        } else {

            return 'Заполните поля ввода';

        }
    }

    public function logout()
    {
        if (isset($_COOKIE['username'])) {

            $rememberToken = bin2hex(random_bytes(16));
            setcookie('rememberToken', '', time() - $this->cookieExpires);
            setcookie('username', '', time() - $this->cookieExpires);
            $find = R::find($this->db['table'], 'username = ?', [$_COOKIE['username']]);
            $addRememberToken = R::load($this->db['table'], $find[1]['id']);
            $addRememberToken->remember_token = $rememberToken;
            R::store($addRememberToken);
            View::redirect($this->logoutRedirect);
        }
    }
}