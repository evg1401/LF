<?php


namespace App\Controllers\Auth;

use Core\Auth\HandlerPasswordReminder;
use Core\Auth\Login;
use Core\Auth\Registration;
use Core\Controller;
use Core\Http\Request;

class AuthController extends Controller
{
    protected $request;

    public function __construct($route)
    {
        parent::__construct($route);
        $this->request = new Request();
    }

    public function registration()
    {
        if ($this->request->isMethod('POST')) {
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $password_confirm = $this->request->getPost('password_confirm');
            $first_name = $this->request->getPost('first_name');
            $last_name = $this->request->getPost('last_name');
            $rememberMe = $this->request->getPost('remember');
            $registration = new Registration();
            $result = $registration->registration($username, $email, $password, $password_confirm, $first_name, $last_name, $rememberMe);
        }
        $this->view->render('Auth/registration.html.twig', compact('result'));
    }

    public function login()
    {
        if ($this->request->isMethod('POST')) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $rememberMe = $this->request->getPost('remember');
            $login = new Login();
            $result = $login->login($username, $password, $rememberMe);
        }
        $this->view->render('Auth/login.html.twig', compact('result'));

    }

    public function passwordRecovery()
    {
        $request = new Request();
        $passwordReminder = new HandlerPasswordReminder();
        $result = $passwordReminder->passwordRecovery($request->getPost('email'));

        if ($request->getPost('submit')) {
            if ($result === null) {
                $res = 'Пароль отправлен на ваш email.';
            } else {
                $res = 'Пользователь с таким email не найден.';
            }
        }
        $this->view->require('Auth/passwordRecovery.php', compact('res'));
    }
}