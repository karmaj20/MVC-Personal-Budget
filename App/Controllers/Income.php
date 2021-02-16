<?php

declare(strict_types = 1);

namespace App\Controllers;

use App\Flash;
use App\Models\Money;
use Core\View;

class Income extends \Core\Controller
{
    public function newAction() : void
    {
        View::renderTemplate('Income/income.html');
    }

    public function createAction()
    {
        $income = new Money($_POST);

        if ($_POST['ammountIncome'] > 0) {

            $income->addIncomeToDatabase();

            Flash::addMessage('Przychód został dodany');

            $this->redirect('/Income');
        } else {

            Flash::addMessage('Przychód nie został dodany, spróbuj jeszcze raz.');

            $this->redirect('/Income');
        }

    }

}