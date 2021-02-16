<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Flash;
use \Core\View;
use App\Models\User;
use App\Auth;

class Login extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Login/login.html');
    }


    public function createAction()
    {
        $user = User::authenticate($_POST['login'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);

        if ($user) {

            Auth::login($user, $remember_me);

            Flash::addMessage('Poprawne logowanie');

            $this->redirect(Auth::getReturnToPage());
        } else {
            Flash::addMessage('Niepoprawne logowanie, spróbuj jeszcze raz.', FLASH::WARNING);

            View::renderTemplate('Login/login.html', [
                'email' => $_POST['login'],
                'remember_me' => $remember_me
            ]);
        }
    }

    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/login/show-logout-message');
    }

    public function showLogoutMessageAction()
    {
        Flash::addMessage('Poprawne wylogowanie');

        $this->redirect('/');
    }

}