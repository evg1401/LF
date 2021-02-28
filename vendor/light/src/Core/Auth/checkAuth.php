<?php

namespace Core\Auth;

use RedBeanPHP\R;

class checkAuth extends Auth
{
    public function check()
    {
        if (isset($_COOKIE['username'])) {
            if (R::find($this->db['table'], 'username = ?', [$_COOKIE['username']])) {
                return true;
            }
        } else {
            if (isset($_COOKIE['rememberToken'])) {
                if ($find = R::find($this->db['table'], 'rememberToken = ?', [$_COOKIE['rememberToken']])) {
                    setcookie('username', $find[1]['username']);
                    return true;
                }
            }
        }
    }
}