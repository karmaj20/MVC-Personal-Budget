<?php

namespace App\Controllers;

use \Core\View;
use App\Auth;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        /* \App\Mail::send('demo@daveh.io', 'Test', 'This is a test', '<h1> This is a test </h1>'); */

        View::renderTemplate('Home/index.html');
    }
}
