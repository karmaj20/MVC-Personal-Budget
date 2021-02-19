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
            'incomeCurrentMonth'    => BalanceMod::loadIncomeCurrentMonth(),
            'expenseCurrentMonth'   => BalanceMod::loadExpenseCurrentMonth(),
            'incomePreviousMonth'   => BalanceMod::loadIncomePreviousMonth(),
            'expensePreviousMonth'  => BalanceMod::loadExpensePreviousMonth(),
            'incomeCurrentYear'     => BalanceMod::loadIncomeCurrentYear(),
            'expenseCurrentYear'    => BalanceMod::loadExpenseCurrentYear()
            //'incomeChosenPeriod'    => BalanceMod::loadIncomeChosenPeriod(),
            //'expenseChosenPeriod'   => BalanceMod::loadExpenseChosenPeriod()
        ]);
    }


}