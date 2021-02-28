<?php


namespace App\Controllers\Auth;

use Core\Auth\HandlerPasswordReminder;
use Core\Auth\Login;
use Core\Auth\Registration;
use Core\Controller;
use Core\Http\Request;
use RedBeanPHP\RedException\SQL;

class AuthController extends Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function registration(): void
    {
        $result = '';

        if ($this->request->isMethod('POST')) {
            $username = $this->request->getPost('username');
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
            $password_confirm = $this->request->getPost('password_confirm');
            $first_name = $this->request->getPost('first_name');
            $last_name = $this->request->getPost('last_name');
            $remember_me = $this->request->getPost('remember_me');
            $registration = new Registration();
            try {
                $result = $registration->registration($username, $email, $password, $password_confirm, $first_name, $last_name, $remember_me);
            } catch (SQL $e) {
            }
        }
        $this->render('Auth/registration.html.twig', compact('result'));
    }

    public function login(): void
    {
        $result = '';

        if ($this->request->isMethod('POST')) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            $rememberMe = $this->request->getPost('remember');
            $login = new Login();
            $result = $login->login($username, $password, $rememberMe);
        }
        $this->render('Auth/login.html.twig', compact('result'));
    }

    public function passwordRecovery()
    {

        $passwordReminder = new HandlerPasswordReminder();
        try {
            $result = $passwordReminder->passwordRecovery($this->request->getPost('email'));
        } catch (SQL $e) {
        }
        $this->render('Auth/passwordRecovery.html.twig', compact('result'));
    }

    public function logout()
    {
        $login = new Login();
        $login->logout();
    }
}