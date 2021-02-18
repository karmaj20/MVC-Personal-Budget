<?php

namespace App\Controllers;

use Core\View;

class Balance extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Balance/balance.html');
    }
}