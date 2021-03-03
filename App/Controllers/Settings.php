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
            'incomesCategory'       => IncomeMod::selectIncomesCategory(),
            'expensesCategory'      => ExpenseMod::selectExpensesCategory(),
            'paymentMethods'        => ExpenseMod::selectPaymentMethodsAssigned(),
            'name'                  => SettingsMod::selectUsername(),
            'email'                 => SettingsMod::selectEmail(),
            'limit'                 => SettingsMod::selectLimit()
//            'insertIncomeCategory'  => IncomeMod::insertIncomeCategory($incomeCategoryName),
//            'insertExpenseCategory' => ExpenseMod::insertExpenseCategory($expenseCategoryName, $expenseLimit),
//            'insertPaymentMethod'   => ExpenseMod::insertPaymentMethod($methodName),
//            'deleteIncomeCategory'  => IncomeMod::deleteIncomeCategory($incomeCategoryName),
//            'deleteExpenseCategory' => ExpenseMod::deleteExpenseCategory($expenseCategoryName),
//            'deletePaymentMethod'   => ExpenseMod::deletePaymentMethod($methodName),
//            'deleteIncomes'         => IncomeMod::deleteIncomes(),
//            'deleteExpenses'        => ExpenseMod::deleteExpenses(),
//            'deleteUserAccount'     => SettingsMod::deleteUserAccount()



        ]);
    }
}