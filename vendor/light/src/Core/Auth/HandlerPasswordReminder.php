<?php


namespace Core\Auth;

use Core\InitConnection;
use RedBeanPHP\R;


/**
 * Class HandlerPasswordReminder
 * @package Core\Auth
 */
class HandlerPasswordReminder extends Auth
{

    /**
     * @var string
     */
    protected string $email;
    /**
     * @var object
     */
    private object $result;

    /**
     * @param string|null $email
     * @return bool
     * @throws \RedBeanPHP\RedException\SQL
     */
    public function passwordRecovery(string $email = null) //Поиск юзера по email, генерация пароля, запись пароля в БД
    {
        if (R::findOne($this->db['table'], $this->db['columns']['email'] . '= ?', [$email])) {
            $password = uniqid();
            $passwordEncrypt = password_hash($password, PASSWORD_ARGON2I);
            $id = R::find($this->db['table'], $this->db['columns']['email'] . '= ?', [$email]);

            foreach ($id as $value) {
                $this->result = $value;
            }

            $userId = $this->result['id'];
            $savePassword = R::findOne($this->db['table'], $userId);
            $savePassword->password = $passwordEncrypt;
            R::store($savePassword);
            $this->email = $email;
            $this->sendEmailToken($password);
            return 'Пароль отправлен на ваш email';

        } else {
            return $email ? 'Пользователь с таким email не найден' : null;
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