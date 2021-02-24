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
}