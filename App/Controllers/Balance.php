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
            'incomeCurrentMonth'        => BalanceMod::getIncomeCurrentMonth(),
            'expenseCurrentMonth'       => BalanceMod::getExpenseCurrentMonth(),
            'incomePreviousMonth'       => BalanceMod::getIncomePreviousMonth(),
            'expensePreviousMonth'      => BalanceMod::getExpensePreviousMonth(),
            'incomeCurrentYear'         => BalanceMod::getIncomeCurrentYear(),
            'expenseCurrentYear'        => BalanceMod::getExpenseCurrentYear(),
            'incomeChosenPeriod'        => BalanceMod::getIncomeChosenPeriod(),
            'expenseChosenPeriod'       => BalanceMod::getExpenseChosenPeriod(),
            'incomeSumUpCurrentMonth'   => BalanceMod::getIncomeSumUpCurrentMonth(),
            'expenseSumUpCurrentMonth'  => BalanceMod::getExpenseSumUpCurrentMonth()
        ]);
    }


}