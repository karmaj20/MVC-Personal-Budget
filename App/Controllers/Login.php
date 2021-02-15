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

            //Auth::login($user, $remember_me);

            // remember the login here

            Flash::addMessage('Login successful');

            $this->redirect(Auth::getReturnToPage());
        } else {
            Flash::addMessage('Login unsuccessful, please try again', FLASH::WARNING);

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
        Flash::addMessage('Logout successful');

        $this->redirect('/');
    }

}