<?php

namespace App\Controllers;

use App\Models\BalanceMod;
use App\Date;
use Core\View;

class detailedBalance extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Balance/detailed.html', [
            'detailedIncomeCurrentMonth'    => BalanceMod::getDetailedIncomeCurrentMonth(),
            'detailedExpenseCurrentMonth'   => BalanceMod::getDetailedExpenseCurrentMonth(),
            'detailedIncomePreviousMonth'   => BalanceMod::getDetailedIncomePreviousMonth(),
            'detailedExpensePreviousMonth'  => BalanceMod::getDetailedExpensePreviousMonth(),
            'detailedIncomeCurrentYear'     => BalanceMod::getDetailedIncomeCurrentYear(),
            'detailedExpenseCurrentYear'    => BalanceMod::getDetailedExpenseCurrentYear(),
            'detailedIncomeChosenPeriod'    => BalanceMod::getDetailedIncomeChosenPeriod(),
            'detailedExpenseChosenPeriod'   => BalanceMod::getDetailedExpenseChosenPeriod(),
            'range' => Date::getRange()
        ]);
    }
}