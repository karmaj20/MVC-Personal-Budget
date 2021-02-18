<?php

namespace App\Controllers;

use App\Flash;
use App\Models\BalanceMod;
use Core\View;

class Balance extends \Core\Controller
{
    public function newAction()
    {
        $balance = new BalanceMod();

        View::renderTemplate('Balance/balance.html', [
            'incomeCategoriesAmount' => BalanceMod::loadIncomeCurrentMonth(),
            'expenseCategoriesAmount' => BalanceMod::loadExpenseCurrentMonth()
        ]);
    }


}