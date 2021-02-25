<?php

namespace App\Controllers;

use Core\View;

class Settings extends Authenticated
{
    public function newAction()
    {
        View::renderTemplate('Settings/settings.html');
    }
}