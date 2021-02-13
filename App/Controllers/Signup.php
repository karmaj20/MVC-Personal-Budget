<?php

declare(strict_types = 1);

namespace App\Controllers;

use \Core\View;
//use App\Auth;


class Signup extends \Core\Controller
{
    public function newAction() : void
    {
        View::renderTemplate('Signup/new.html');
    }
}