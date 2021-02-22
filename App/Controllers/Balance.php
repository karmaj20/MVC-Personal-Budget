<?php

namespace App\Controllers;

use App\Models\BalanceMod;
use App\Date;
use Core\View;


class Balance extends \Core\Controller
{
    public function newAction()
    {
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
            'expenseSumUpCurrentMonth'  => BalanceMod::getExpenseSumUpCurrentMonth(),
            'incomeSumUpPreviousMonth'  => BalanceMod::getIncomeSumUpPreviousMonth(),
            'expenseSumUpPreviousMonth' => BalanceMod::getExpenseSumUpPreviousMonth(),
            'incomeSumUpCurrentYear'    => BalanceMod::getIncomeSumUpCurrentYear(),
            'expenseSumUpCurrentYear'   => BalanceMod::getExpenseSumUpCurrentYear(),
            'incomeSumUpChosenPeriod'   => BalanceMod::getIncomeSumUpChosenPeriod(),
            'expenseSumUpChosenPeriod'  => BalanceMod::getExpenseSumUpChosenPeriod(),
            'range' => Date::getRange()
        ]);
    }


}