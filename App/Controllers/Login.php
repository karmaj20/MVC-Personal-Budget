<?php

namespace App\Controllers;

use App\Flash;
use \Core\View;
use App\Models\User;
use App\Auth;

/**
 * Login controller
 *xxxxx
 * PHP version 7.0
 */
class Login extends \Core\Controller
{

    /**
     * Show the login page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Login/login.html');
    }

    /**
     * Log in a user
     *
     * @return void
     */
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);

        $remember_me = isset($_POST['remember_me']);


        if($user){

            $_SESSION['id'] = $user->id;

            Auth::login($user, $remember_me);

            Flash::addMessage('Poprawne logowanie');

            $this->redirect('/Income');

        }else{
            Flash::addMessage('Niepoprawne logowanie, spróbuj ponownie', FLASH::WARNING);

            View::renderTemplate('Login/login.html',[
                'email' => $_POST['email'],
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
        Flash::addMessage('Poprawne wylogowanie', FLASH::INFO);

        $this->redirect('/');
    }

    public function showDeleteMessageAction()
    {
        Flash::addMessage('Konto zostało usunięte', FLASH::INFO);

        $this->redirect('/');
    }
}
