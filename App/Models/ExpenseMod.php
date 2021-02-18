<?php

namespace App\Models;

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

        if (isset($_POST['ammountExpense'])) {

            $ammountExpense = $_POST['ammountExpense'];
            $ammountExpense = str_replace(",", ".", $ammountExpense);
            $dateExpense = $_POST['dateExpense'];
            $paymentMethod = $_POST['paymentMethod'];
            $categoryExpense = $_POST['categoryExpense'];
            $commentExpense = $_POST['commentExpense'];

            $sql = "INSERT INTO expenses
                    VALUES (null, :id, :categoryExpense, :paymentMethod, :ammountExpense, :dateExpense, :commentExpense)";

            $database = static::getDB();
            $stmt = $database->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':categoryExpense',    $this->categoryExpense, PDO::PARAM_INT);
            $stmt->bindValue(':paymentMethod',      $this->paymentMethod,   PDO::PARAM_STR);
            $stmt->bindValue(':ammountExpense',     $this->ammountExpense,  PDO::PARAM_STR);
            $stmt->bindValue(':dateExpense',        $this->dateExpense,     PDO::PARAM_STR);
            $stmt->bindValue(':commentExpense',     $this->commentExpense,  PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }
}