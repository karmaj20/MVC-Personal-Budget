<?php

namespace App\Models;

use PDO;

class IncomeMod extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function addIncomeToDatabase() : bool
    {
        $id = (int)$_SESSION['id'];

        if (isset($_POST['amountIncome'])) {

            $amountIncome = $_POST['amountIncome'];
            $amountIncome = str_replace(",", ".", $amountIncome);
            $dateIncome = $_POST['dateIncome'];
            $categoryIncome = $_POST['categoryIncome'];
            $commentIncome = $_POST['commentIncome'];

            $sql = "INSERT INTO incomes
                    VALUES (null, :id, :categoryIncome, :amountIncome, :dateIncome, :commentIncome)";

            $database = static::getDB();
            $stmt = $database->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':categoryIncome', $categoryIncome, PDO::PARAM_STR);
            $stmt->bindValue(':amountIncome',   $amountIncome,  PDO::PARAM_STR);
            $stmt->bindValue(':dateIncome',     $dateIncome,     PDO::PARAM_STR);
            $stmt->bindValue(':commentIncome',  $commentIncome,  PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public static function selectIncomesCategory()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT incCat.name 
                FROM incomes_category_assigned_to_users AS incCat 
                WHERE incCat.user_id = '$id'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function insertIncomeCategory($incomeCategoryName)
    {
        $id = $_SESSION['id'];

        $sql = "
               INSERT INTO incomes_category_assigned_to_users 
               VALUES (null, '$id', '$incomeCategoryName')
               ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function deleteIncomeCategory($incomeCategoryName)
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM incomes_category_assigned_to_users
                WHERE user_id = '$id' AND name = '$incomeCategoryName'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function deleteIncomes()
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM incomes 
                WHERE user_id = '$id'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}