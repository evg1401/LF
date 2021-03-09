<?php

namespace Core\Auth;

use RedBeanPHP\R;

class checkAuth extends Auth
{
    /**
     * @var object
     */
    private object $result;

    /**
     * @return bool
     */
    public function check()
    {
        if (isset($_COOKIE['username'])) {
            if (R::find($this->db['table'], 'username = ?', [$_COOKIE['username']])) {
                return true;
            }
        } else {
            if (isset($_COOKIE['rememberToken'])) {
                if ($find = R::find($this->db['table'], 'rememberToken = ?', [$_COOKIE['rememberToken']])) {

                    foreach ($find as $value) {

                        $this->result = $value;
                    }

                    setcookie('username', $this->result['username']);
                    return true;
                }
            }
        }
    }
}