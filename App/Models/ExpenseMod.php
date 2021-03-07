<?php

namespace App\Models;

use App\Flash;
use PDO;

class ExpenseMod extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function addExpenseToDatabase() : bool
    {
        $id = (int)$_SESSION['id'];

        if (isset($_POST['amountExpense'])) {

            $amountExpense = $_POST['amountExpense'];
            $amountExpense = str_replace(",", ".", $amountExpense);
            $dateExpense = $_POST['dateExpense'];
            $paymentMethod = $_POST['paymentMethod'];
            $categoryExpense = $_POST['categoryExpense'];
            $commentExpense = $_POST['commentExpense'];

            $sql = "INSERT INTO expenses
                    VALUES (null, :id, :categoryExpense, :paymentMethod, :amountExpense, :dateExpense, :commentExpense)";

            $database = static::getDB();
            $stmt = $database->prepare($sql);

            $stmt->bindValue(':id',                 $id,              PDO::PARAM_INT);
            $stmt->bindValue(':categoryExpense',    $categoryExpense, PDO::PARAM_INT);
            $stmt->bindValue(':paymentMethod',      $paymentMethod,   PDO::PARAM_STR);
            $stmt->bindValue(':amountExpense',      $amountExpense,   PDO::PARAM_STR);
            $stmt->bindValue(':dateExpense',        $dateExpense,     PDO::PARAM_STR);
            $stmt->bindValue(':commentExpense',     $commentExpense,  PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function insertExpenseCategory()
    {
        $id = $_SESSION['id'];

        if(isset($_GET['expense'])) {
            $expenseCategoryName = $_GET['expense'];

            $expenseCategoryName = SettingsMod::convertTextToFirstCapitalize($expenseCategoryName);

            if(isset($_GET['addingLimitAmount'])) {
                $expenseLimit = $_GET['addingLimitAmount'];
            } else {
                $expenseLimit = null;
            }

            $sql = "
               INSERT INTO expenses_category_assigned_to_users 
               VALUES (null, :id, :expenseCategoryName, :expenseLimit)
               ";

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id',                     $id,                  PDO::PARAM_INT);
            $stmt->bindValue(':expenseCategoryName',    $expenseCategoryName, PDO::PARAM_STR);
            $stmt->bindValue(':expenseLimit',           $expenseLimit,        PDO::PARAM_INT);

            $result = $stmt->fetchAll();
            $stmt->execute();

            return $result;
        }

        return false;
    }

    public function insertPaymentMethod()
    {
        $id = $_SESSION['id'];

        if(isset($_GET['method'])) {

            $methodName = $_GET['method'];

            $methodName = SettingsMod::convertTextToFirstCapitalize($methodName);

            $sql = "
               INSERT INTO payment_methods_assigned_to_users 
               VALUES (null, :id, :methodName)
               ";

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id',             $id,         PDO::PARAM_INT);
            $stmt->bindValue(':methodName',     $methodName, PDO::PARAM_STR);

            $result = $stmt->fetchAll();
            $stmt->execute();

            return $result;
        }

        return false;
    }

    public function updateExpenseCategory()
    {
        $id = $_SESSION['id'];

        $count = sizeof(ExpenseMod::selectExpensesCategory());
        $expenseName = ExpenseMod::selectExpensesCategory();

        $j = 0;
        for ($i = 1; $i <= $count; $i++) {
            if ($_GET['editExpenseCategory' .$i] != $expenseName[$j]['category'] ||
                $_GET['editionLimitAmount' . $i] != $expenseName[$j]['expense_limit']) {

                $oldExpenseCategoryName = $expenseName[$j]['category'];
                $newExpenseCategoryName = $_GET['editExpenseCategory' . $i];
                $newExpenseLimitName = $_GET['editionLimitAmount' . $i];
            }
//            if($_GET['editionLimitAmount' . $i] != $expenseName[$j]['expense_limit']) {
//                $newExpenseLimitName = $_GET['editionLimitAmount' . $i];
//                $newExpenseCategoryName = $_GET['editExpenseCategory' . $i];
//            }
            $j++;
        }

        $sqlExpenseCategory =
                "
                UPDATE expenses_category_assigned_to_users
                SET category = :newExpenseCategoryName, expense_limit = :newExpenseLimitName
                WHERE category = :oldExpenseCategoryName AND user_id = :id
                ";

        $db = static::getDB();
        $stmt_category = $db->prepare($sqlExpenseCategory);

        $stmt_category->bindValue(':id',                            $id,                     PDO::PARAM_INT);
        $stmt_category->bindValue(':newExpenseCategoryName',        $newExpenseCategoryName, PDO::PARAM_STR);
        $stmt_category->bindValue(':oldExpenseCategoryName',        $oldExpenseCategoryName, PDO::PARAM_STR);
        $stmt_category->bindValue(':newExpenseLimitName',           $newExpenseLimitName,    PDO::PARAM_INT);

        $stmt_category->execute();


        return $stmt_category->execute();
    }

    public function updatePaymentMethod()
    {
        $id = $_SESSION['id'];

        $count = sizeof(ExpenseMod::selectPaymentMethodsAssigned());
        $paymentMethodName = ExpenseMod::selectPaymentMethodsAssigned();

        $oldPaymentMethodName = "";
        $newPaymentMethodName = "";

        $j = 0;
        for($i = 1; $i <= $count; $i++) {
            if($_GET['editPaymentMethod' . $i] != $paymentMethodName[$j]['method']) {
                $oldPaymentMethodName = $paymentMethodName[$j]['method'];
                $newPaymentMethodName = $_GET['editPaymentMethod' . $i];
            }
            $j++;
        }

        $sql = "
                UPDATE payment_methods_assigned_to_users
                SET method = :newPaymentMethodName
                WHERE method = :oldPaymentMethodName AND user_id = :id
               ";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                             $id,                   PDO::PARAM_INT);
        $stmt->bindValue(':oldPaymentMethodName',           $oldPaymentMethodName, PDO::PARAM_STR);
        $stmt->bindValue(':newPaymentMethodName',           $newPaymentMethodName, PDO::PARAM_STR);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    public function deleteExpenseCategory()
    {
        $id = $_SESSION['id'];

        if (isset($_GET['deleteExpenseCategory'])) {

            $expenseCategoryName = $_GET['deleteExpenseCategory'];

            $tab = $this->selectIdDeletedExpensesCategory($expenseCategoryName);
            $idDeleteCategory = 0;

            for($i = 0; $i < sizeof($tab); $i++) {
                if ($expenseCategoryName == $tab[$i]['category'])
                    (int) $idDeleteCategory = $tab[$i]['id'];
            }

            $this->updateExpensesDeletedCategory($idDeleteCategory);

            $sql = "
                    DELETE FROM expenses_category_assigned_to_users
                    WHERE user_id = :id AND category = :expenseCategoryName
                    ";

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id',                     $id,                  PDO::PARAM_INT);
            $stmt->bindValue(':expenseCategoryName',    $expenseCategoryName, PDO::PARAM_STR);

            $result = $stmt->fetchAll();
            $stmt->execute();

            return $result;
        }

        return false;
    }

    public function deletePaymentMethod()
    {
        $id = $_SESSION['id'];

        if (isset($_GET['deletePaymentMethod'])) {

            $methodName = $_GET['deletePaymentMethod'];

            $tab = $this->selectIdDeletedMethodCategory($methodName);
            $idDeleteMethod = 0;

            for($i = 0; $i < sizeof($tab); $i++) {
                if ($methodName == $tab[$i]['method']) {
                    $idDeleteMethod = $tab[$i]['id'];
                }
            }

            $this->updateDeletedPaymentMethod($idDeleteMethod);

            $sql = "
                    DELETE FROM payment_methods_assigned_to_users
                    WHERE user_id = :id AND method = :methodName
                    ";

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id',            $id,         PDO::PARAM_INT);
            $stmt->bindValue(':methodName',    $methodName, PDO::PARAM_STR);

            $result = $stmt->fetchAll();
            $stmt->execute();

            return $result;
        }
    }

    public function deleteSingleExpense()
    {
        $id = $_POST['binExpense'];

        $sql = "
               DELETE FROM expenses
               WHERE id = :id
               ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',  $id,PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    public static function selectExpensesCategory()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT exCat.category, exCat.id, exCat.expense_limit
                FROM expenses_category_assigned_to_users AS exCat 
                WHERE exCat.user_id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function selectPaymentMethodsAssigned()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT payMet.method, payMet.id 
                FROM payment_methods_assigned_to_users AS payMet 
                WHERE payMet.user_id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function deleteExpenses()
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM expenses 
                WHERE user_id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    private function updateExpensesDeletedCategory($deletedCategory)
    {
        $id = $_SESSION['id'];

        $inneWydatkiCategory = $this->selectIdExpenseInneWydatkiCategory();

        $inneWydatkiID = 0;

        for($i = 0; $i < sizeof($inneWydatkiCategory); $i++) {
            $inneWydatkiID = $inneWydatkiCategory[$i]['id'];
        }

        $sql = "
                UPDATE expenses 
                SET expense_category_assigned_to_user_id = :inneWydatkiID 
                WHERE expenses.user_id = :id AND expense_category_assigned_to_user_id = :deletedCategory
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                     $id,                PDO::PARAM_INT);
        $stmt->bindValue(':inneWydatkiID',          $inneWydatkiID,     PDO::PARAM_INT);
        $stmt->bindValue(':deletedCategory',        $deletedCategory,   PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    private function selectIdExpenseInneWydatkiCategory()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT id
                FROM expenses_category_assigned_to_users AS exCat 
                WHERE exCat.user_id = :id AND category = 'Inne Wydatki'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function selectIdDeletedExpensesCategory($deleteCategory)
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT id, category
                FROM expenses_category_assigned_to_users AS exCat 
                WHERE exCat.user_id = :id AND category = :deleteCategory
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                 $id,             PDO::PARAM_INT);
        $stmt->bindValue(':deleteCategory',     $deleteCategory, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function updateDeletedPaymentMethod($deletedPaymentMethod)
    {
        $id = $_SESSION['id'];

        $paymentMethodCategory = $this->selectIdMethodCategory();

        $methodID = 0;

        for($i = 0; $i < sizeof($paymentMethodCategory); $i++) {
            $methodID = $paymentMethodCategory[$i]['id'];
        }

        $sql = "
                UPDATE expenses 
                SET payment_method_assigned_to_user_id = :methodID 
                WHERE user_id = :id AND payment_method_assigned_to_user_id = :deletedPaymentMethod
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                         $id,                    PDO::PARAM_INT);
        $stmt->bindValue(':methodID',                   $methodID,              PDO::PARAM_INT);
        $stmt->bindValue(':deletedPaymentMethod',       $deletedPaymentMethod,  PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    private function selectIdMethodCategory()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT id
                FROM payment_methods_assigned_to_users AS payMet 
                WHERE payMet.user_id = :id AND method = 'Inna forma płatności'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function selectIdDeletedMethodCategory($deleteMethod)
    {
        $id = $_SESSION['id'];


        $sql = "
                SELECT id, method
                FROM payment_methods_assigned_to_users AS payMet 
                WHERE payMet.user_id = :id AND method = :deleteMethod
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                 $id,             PDO::PARAM_INT);
        $stmt->bindValue(':deleteMethod',       $deleteMethod,   PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}