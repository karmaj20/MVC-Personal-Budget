<?php

namespace App\Controllers;

use App\Models\ExpenseMod;
use App\Models\IncomeMod;
use App\Models\SettingsMod;
use Core\View;

class Settings extends Authenticated
{
    public function newAction()
    {
        View::renderTemplate('Settings/settings.html',[
            'incomesCategory'  => IncomeMod::selectIncomesCategory(),
            'expensesCategory' => ExpenseMod::selectExpensesCategory(),
            'paymentMethods'   => ExpenseMod::selectPaymentMethodsAssigned(),
            'name'             => SettingsMod::selectUsername(),
            'email'            => SettingsMod::selectEmail()
        ]);
    }
}