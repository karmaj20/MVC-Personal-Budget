<?php

declare(strict_types = 1);

namespace App\Models;

use PDO;

class Money extends \Core\Model
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

        if (isset($_POST['ammountIncome'])) {

            $ammountIncome = $_POST['ammountIncome'];
            $ammountIncome = str_replace(",", ".", $ammountIncome);
            $dateIncome = $_POST['dateIncome'];
            $categoryIncome = $_POST['categoryIncome'];
            $commentIncome = $_POST['commentIncome'];

            $sql = "INSERT INTO incomes
                    VALUES (null, :id, :categoryIncome, :ammountIncome, :dateIncome, :commentIncome)";

            $database = static::getDatabase();
            $stmt = $database->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':categoryIncome', $this->categoryIncome, PDO::PARAM_STR);
            $stmt->bindValue(':ammountIncome', $this->ammountIncome, PDO::PARAM_STR);
            $stmt->bindValue(':dateIncome', $this->dateIncome, PDO::PARAM_STR);
            $stmt->bindValue(':commentIncome', $this->commentIncome, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }
}