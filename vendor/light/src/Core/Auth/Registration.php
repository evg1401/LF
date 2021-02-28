<?php

namespace Core\Auth;

use RedBeanPHP\R;

/**
 * Class Registration
 * @package Core\Auth
 */
class Registration extends Auth
{
    /**
     * @param $username
     * @param $email
     * @param $password
     * @param $password_confirm
     * @param $first_name
     * @param $last_name
     * @return string
     * @throws \RedBeanPHP\RedException\SQL
     */
    public function registration($username, $email, $password, $password_confirm, $first_name, $last_name, $remember_me)
    {
        $this->data['username'] = $username;
        $this->data['email'] = $email;
        $this->data['password'] = $password;
        $this->data['password_confirm'] = $password_confirm;
        $this->data['first_name'] = $first_name;
        $this->data['last_name'] = $last_name;
        $this->data['remember_me'] = $remember_me;

        if (!$this->checkEmptyFieldsRegistration($this->data)) {
            if ($this->checkColumnMatch($this->data)) {

                if ($this->data['remember_me'] === 'yes') {
                    $this->data['remember_token'] = bin2hex(random_bytes(16));
                }

                $table = R::dispense($this->db['table']);
                $table[$this->db['columns']['username']] = $this->data['username'];
                $table[$this->db['columns']['email']] = $this->data['email'];
                $table[$this->db['columns']['password']] = password_hash($this->data['password'], PASSWORD_ARGON2I);
                $table[$this->db['columns']['first_name']] = $this->data['first_name'];
                $table[$this->db['columns']['last_name']] = $this->data['last_name'];

                if (isset($this->data['remember_token'])) {
                    $table[$this->db['columns']['remember_token']] = $this->data['remember_token'];
                    setcookie('rememberToken', $this->data['remember_token'], time() + $this->cookieExpires);
                }

                R::store($table);

                setcookie('username', $this->data['username'], time() + $this->cookieExpires);

                return 'регистрация завершена успешно';


            } else {
                return 'Пользователь с таким логином или email уже зарегистрирован';
            }

        } else {
            $errors[] = $this->checkEmptyFieldsRegistration($this->data);
            return $errors;
        }
    }
}