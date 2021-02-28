<?php

namespace App\Controllers;

use App\Date;
use App\Flash;
use App\Models\ExpenseMod;
use Core\View;

class Expense extends Authenticated
{
    public function newAction()
    {
        View::renderTemplate('Expense/expense.html',[
            'date' => Date::getCurrentDate(),
            'expensesCategory' => ExpenseMod::selectExpensesCategory(),
            'paymentMethods' => ExpenseMod::selectPaymentMethodsAssigned()
        ]);
    }

    public function createAction()
    {
        $expense = new ExpenseMod($_POST);

        if ($_POST['amountExpense'] > 0) {

            $expense->addExpenseToDatabase();

            Flash::addMessage('Wydatek został dodany.');

            $this->redirect('/Expense');
        } else {

            Flash::addMessage('Wydatek nie został dodany, spróbuj jeszcze raz.');

            $this->redirect('/Expense');
        }
    }
}