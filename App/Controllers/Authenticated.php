<?php

declare(strict_types = 1);

namespace App\Controllers;

abstract class Authenticated extends \Core\Controller
{
    protected function before() : void
    {
        $this->requireLogin();
    }
}