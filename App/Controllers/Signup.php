<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Controllers\Account;
use App\Models\User;
use \Core\View;
use App\Flash;
//use App\Auth;


class Signup extends \Core\Controller
{
    public function newAction() : void
    {
        View::renderTemplate('Signup/new.html');
    }

    public function createAction() : void
    {
        $user = new User($_POST);

        if (Account::validateEmailAction($user->email) == true) {

            $user->saveUserToDatabase();

            Flash::addMessage('Poprawna rejestracja, możesz się zalogować.');

            $this->redirect('/Login');

        } else {

            Flash::addMessage('Rejestracja niepoprawna, istnieje użytkownik o takich danych');

            View::renderTemplate('Signup/new.html');
        }
    }

    public function successAction() : void
    {
        View::renderTemplate('Login/login.html');
    }

}