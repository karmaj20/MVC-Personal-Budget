<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Models\User;
use \Core\View;
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

        if ($user->saveUserToDatabase()) {

            $this->redirect('/signup/success');
        } else {

            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);
        }
    }

    public function successAction() : void
    {
        View::renderTemplate('Signup/success.html');
    }

}