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
     * @var object
     */
    private object $result;

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

            foreach ($find as $value) {
                $this->result = $value;
            }

            if ($this->result['username'] === $this->data['username'] && password_verify($this->data['password'], $this->result['password'])) {

                setcookie('username', $this->result['username']);

                if ($rememberMe === 'yes') {
                    if ($this->result['remember_token'] !== '') {

                        setcookie('rememberToken', $this->result['remember_token'], time() + $this->cookieExpires);

                    } else {

                        $rememberToken = bin2hex(random_bytes(16));
                        setcookie('rememberToken', $rememberToken, time() + $this->cookieExpires);
                        $addRememberToken = R::load($this->db['table'], $this->result['id']);
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

            foreach ($find as $value) {
                $this->result = $value;
            }

            $addRememberToken = R::load($this->db['table'], $this->result['id']);
            $addRememberToken->remember_token = $rememberToken;
            R::store($addRememberToken);
            View::redirect($this->logoutRedirect);
        }
    }
}