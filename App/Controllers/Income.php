<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Flash;
use Core\View;
use PDO;

class Income extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Income/income.html');
    }

}