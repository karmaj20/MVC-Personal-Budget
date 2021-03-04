<?php

namespace App\Controllers;

use App\Models\BalanceMod;
use App\Date;
use App\Models\IncomeMod;
use App\Models\SettingsMod;
use Core\View;

class DetailedBalance extends Authenticated
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