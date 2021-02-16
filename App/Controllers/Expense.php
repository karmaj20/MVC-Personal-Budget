<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Flash;
use App\Models\Money;
use Core\View;

class Expense extends \Core\Controller
{
    public function newAction()
    {
        View::renderTemplate('Expense/expense.html');
    }

    public function createAction()
    {
        $expense = new Money($_POST);

        if ($_POST['ammountExpense'] > 0) {

            $expense->addExpenseToDatabase();

            Flash::addMessage('Wydatek został dodany.');

            $this->redirect('/Expense');
        } else {

            Flash::addMessage('Wydatek nie został dodany, spróbuj jeszcze raz.');

            $this->redirect('/Expense');
        }
    }
}