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
        View::renderTemplate('Settings/settings.html', [
            'incomesCategory' => IncomeMod::selectIncomesCategory(),
            'expensesCategory' => ExpenseMod::selectExpensesCategory(),
            'paymentMethods' => ExpenseMod::selectPaymentMethodsAssigned(),
            'name' => SettingsMod::selectUsername(),
            'email' => SettingsMod::selectEmail(),
            'limit' => SettingsMod::selectLimit()
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

    public function deleteIncomeCategoryAction()
    {
        $delete = new IncomeMod($_GET);

        if (isset($_GET['deleteIncomeCategory'])) {

            $delete->deleteIncomeCategory();

            Flash::addMessage('Kategoria została usunięta', FLASH::WARNING);

            $this->redirect('/Settings');
        } else {

            Flash::addMessage("Nie udało się usunąc kateogrii, spróbuj jeszcze raz.", Flash::INFO);

            $this->redirect('/Settings');
        }
    }

    public function deleteExpenseCategoryAction()
    {
        $delete = new ExpenseMod($_GET);

        if (isset($_GET['deleteExpenseCategory'])) {

            $delete->deleteExpenseCategory();

            Flash::addMessage('Kategoria została usunięta', FLASH::WARNING);

            $this->redirect('/Settings');
        } else {

            Flash::addMessage("Nie udało się usunąc kateogrii, spróbuj jeszcze raz.", Flash::INFO);

            $this->redirect('/Settings');
        }
    }

    public function deletePaymentMethodAction()
    {
        $delete = new ExpenseMod($_GET);

        if (isset($_GET['deletePaymentMethod'])) {

            $delete->deletePaymentMethod();

            Flash::addMessage('Metoda płatności została usunięta', FLASH::WARNING);

            $this->redirect('/Settings');
        } else {

            Flash::addMessage("Nie udało się usunąc metody płatności, spróbuj jeszcze raz.", Flash::INFO);

            $this->redirect('/Settings');
        }
    }

    public function deleteIncomesAndExpensesAction()
    {
        $deleteIncomes  = new IncomeMod($_GET);
        $deleteExpenses = new ExpenseMod($_GET);

        if (isset($_GET['deleteIncomesExpenses'])) {

            $deleteIncomes->deleteIncomes();
            $deleteExpenses->deleteExpenses();

            Flash::addMessage('Przychody oraz wydatki zostały usunięte', FLASH::SUCCESS);

            $this->redirect('/Settings');
        } else {

            Flash::addMessage('Nie udało się usunąć przychodów oraz wydatków, spróbuj ponownie.', FLASH::INFO);

            $this->redirect('/Settings');
        }
    }

    public function deleteSingleIncomeAction()
    {
        $delete = new IncomeMod($_POST);

        if(isset($_POST['binIncome'])) {

            $delete->deleteSingleIncome();

            Flash::addMessage('Przychód został usunięty.', FLASH::SUCCESS);

            $this->redirect('/detailed');

        } else {

            Flash::addMessage('Nie udało się usunąć przychodu.', FLASH::INFO);

            $this->redirect('/detailed');
        }
    }

    public function deleteSingleExpenseAction()
    {
        $delete = new ExpenseMod($_POST);

        if(isset($_POST['binExpense'])) {

            $delete->deleteSingleExpense();

            Flash::addMessage('Wydatek został usunięty.', FLASH::SUCCESS);

            $this->redirect('/detailed');

        } else {

            Flash::addMessage('Nie udało się usunąć wydatku.', FLASH::INFO);

            $this->redirect('/detailed');
        }
    }
}