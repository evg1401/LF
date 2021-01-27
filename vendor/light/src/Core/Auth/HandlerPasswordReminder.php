<?php


namespace Core\Auth;

use Core\InitConnection;
use RedBeanPHP\R;


/**
 * Class HandlerPasswordReminder
 * @package Core\Auth
 */
class HandlerPasswordReminder
{

    /**
     * @var string
     */
    protected string $email;


    /**
     * HandlerPasswordReminder constructor.
     */
    public function __construct()
    {
        InitConnection::initConnection(); //установка соединения с БД
        if (!R::testConnection()) die('No DB connection!');
    }


    /**
     * @param string|null $email
     * @return bool
     * @throws \RedBeanPHP\RedException\SQL
     */
    public function passwordRecovery(string $email = null) //Поиск юзера по email, генерация пароля, запись пароля в БД
    {
        if (R::findOne('auth', 'email = ?', [$email])) {
            $password = uniqid();
            $passwordEncrypt = password_hash($password, PASSWORD_ARGON2I);
            $id = R::find('auth', 'email = ?', [$email]);
            $userId = $id[1]['id'];
            $savePassword = R::findOne('auth', $userId);
            $savePassword->password = $passwordEncrypt;
            R::store($savePassword);
            $this->email = $email;
            $this->sendEmailToken($password);
        } else {
            return false;
        }
    }

    /**
     * @param null $password
     */
    protected function sendEmailToken($password = null) //отправка пароля на почту
    {
        mail($this->email, 'Восстановление доступа к аккаунту', 'Пароль' . '  ' . $password, 'Content-Type: text/html;charset=utf-8');
    }
}