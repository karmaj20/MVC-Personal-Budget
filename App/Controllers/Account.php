<?php

namespace App\Controllers;

use App\Flash;
use \App\Models\User;

class Account extends \Core\Controller
{
    // validate if email is available for a new signup

    public static function validateEmailAction(string $email) : bool
    {
        $is_valid = ! User::emailExists($email, $_GET['ignore_id'] ?? null);

        //header('Content-Type: application/json');
        if (json_encode($is_valid) == "true") {

            return true;

        } else {

            return false;

        }
    }
}