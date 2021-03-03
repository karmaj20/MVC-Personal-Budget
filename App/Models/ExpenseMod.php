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

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':categoryExpense',    $categoryExpense, PDO::PARAM_INT);
            $stmt->bindValue(':paymentMethod',      $paymentMethod,   PDO::PARAM_STR);
            $stmt->bindValue(':amountExpense',      $amountExpense,  PDO::PARAM_STR);
            $stmt->bindValue(':dateExpense',        $dateExpense,     PDO::PARAM_STR);
            $stmt->bindValue(':commentExpense',     $commentExpense,  PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public static function selectExpensesCategory()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT exCat.name 
                FROM expenses_category_assigned_to_users AS exCat 
                WHERE exCat.user_id = '$id'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function selectPaymentMethodsAssigned()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT payMet.name 
                FROM payment_methods_assigned_to_users AS payMet 
                WHERE payMet.user_id = '$id'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function insertExpenseCategory($expenseCategoryName, $expenseLimit)
    {
        $id = $_SESSION['id'];

        $sql = "
               INSERT INTO expenses_category_assigned_to_users 
               VALUES (null, '$id', '$expenseCategoryName', '$expenseLimit')
               ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function deleteExpenseCategory($expenseCategoryName)
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM expenses_category_assigned_to_users
                WHERE user_id = '$id' AND name = '$expenseCategoryName'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function insertPaymentMethod($methodName)
    {
        $id = $_SESSION['id'];

        $sql = "
               INSERT INTO payment_methods_assigned_to_users 
               VALUES (null, '$id', '$methodName')
               ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function deletePaymentMethod($methodName)
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM payment_methods_assigned_to_users
                WHERE user_id = '$id' AND name = '$methodName'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function deleteExpenses()
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM expenses 
                WHERE user_id = '$id'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}