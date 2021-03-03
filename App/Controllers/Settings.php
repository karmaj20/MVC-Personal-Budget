<?php

namespace App\Controllers;

use App\Flash;
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
//            'deleteIncomeCategory'  => IncomeMod::deleteIncomeCategory($incomeCategoryName),
//            'deleteExpenseCategory' => ExpenseMod::deleteExpenseCategory($expenseCategoryName),
//            'deletePaymentMethod'   => ExpenseMod::deletePaymentMethod($methodName),
//            'deleteIncomes'         => IncomeMod::deleteIncomes(),
//            'deleteExpenses'        => ExpenseMod::deleteExpenses(),
//            'deleteUserAccount'     => SettingsMod::deleteUserAccount()

        ]);
    }

    public function createIncomeAction()
    {
        $income = new IncomeMod($_GET);

        if (isset($_GET['income'])) {

            $income->insertIncomeCategory();

            Flash::addMessage('Kategoria przychodu została dodana', FLASH::INFO);

            $this->redirect('/Settings');
        } else {

            Flash::addMessage('Kategoria przychodu nie została dodana, spróbuj jeszcze raz.');

            $this->redirect('/Settings');
        }
    }

    public function createExpenseAction()
    {
        $expense = new ExpenseMod($_GET);

        if (isset($_GET['expense'])) {

            $expense->insertExpenseCategory();

            Flash::addMessage('Kategoria wydatku została dodana', FLASH::INFO);

            $this->redirect('/Settings');
        } else {

            Flash::addMessage('Kategoria wydatku nie została dodana, spróbuj jeszcze raz.');

            $this->redirect('/Settings');
        }
    }

    public function createPaymentAction()
    {
        $method = new ExpenseMod($_GET);

        if (isset($_GET['method'])) {

            $method->insertPaymentMethod();

            Flash::addMessage('Metoda płatności została dodana', FLASH::INFO);

            $this->redirect('/Settings');
        } else {

            Flash::addMessage('Metoda płatności nie została dodana, spróbuj jeszcze raz.');

            $this->redirect('/Settings');
        }
    }

}