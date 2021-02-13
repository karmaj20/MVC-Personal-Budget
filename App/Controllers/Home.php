<?php

declare(strict_types = 1);

namespace App\Controllers;

use \Core\View;
//use App\Auth;


class Home extends \Core\Controller
{
    public function indexAction() : void
    {
        View::renderTemplate('Home/menu.html');
    }
}