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

            $stmt->bindValue(':id',             $id,             PDO::PARAM_INT);
            $stmt->bindValue(':categoryIncome', $categoryIncome, PDO::PARAM_INT);
            $stmt->bindValue(':amountIncome',   $amountIncome,   PDO::PARAM_STR);
            $stmt->bindValue(':dateIncome',     $dateIncome,     PDO::PARAM_STR);
            $stmt->bindValue(':commentIncome',  $commentIncome,  PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function insertIncomeCategory()
    {
        $id = $_SESSION['id'];

        if(isset($_GET['income'])) {

            $incomeCategoryName = $_GET['income'];

            $incomeCategoryName = SettingsMod::convertTextToFirstCapitalize($incomeCategoryName);

            $sql = "
                   INSERT INTO incomes_category_assigned_to_users 
                   VALUES (null, :id, :incomeCategoryName)
                   ";

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id',                     $id,                 PDO::PARAM_INT);
            $stmt->bindValue(':incomeCategoryName',     $incomeCategoryName, PDO::PARAM_STR);

            $result = $stmt->fetchAll();
            $stmt->execute();

            return $result;
        }

        return false;
    }

    public function deleteIncomeCategory()
    {
        $id = $_SESSION['id'];

        if (isset($_GET['deleteIncomeCategory'])) {

            $incomeCategoryName = $_GET['deleteIncomeCategory'];

            $tab = $this->selectIdDeletedIncomesCategory($incomeCategoryName);
            $idDeleteCategory = 0;


            for($i = 0; $i < sizeof($tab); $i++) {
                if ($incomeCategoryName == $tab[$i]['category'])
                    $idDeleteCategory = $tab[$i]['id'];
            }

            $this->updateIncomesDeletedCategory($idDeleteCategory);

            $sql = "
                    DELETE FROM incomes_category_assigned_to_users
                    WHERE user_id = :id AND category = :incomeCategoryName
                    ";

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id',                     $id,                 PDO::PARAM_INT);
            $stmt->bindValue(':incomeCategoryName',     $incomeCategoryName, PDO::PARAM_STR);

            $result = $stmt->fetchAll();
            $stmt->execute();

            return $result;
        }

        return false;
    }

    public function deleteSingleIncome()
    {
        $id = $_POST['binIncome'];

        $sql = "
               DELETE FROM incomes
               WHERE id = :id
               ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',  $id,PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    public function deleteIncomes()
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM incomes 
                WHERE user_id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',     $id,    PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    public function updateIncomeCategory()
    {
        $id = $_SESSION['id'];

        $count = sizeof(IncomeMod::selectIncomesCategory());
        $incomeName = IncomeMod::selectIncomesCategory();

        $oldIncomeCategoryName = "";
        $newIncomeCategoryName = "";

        $j = 0;
        for ($i = 1; $i <= $count; $i++) {
            if($_GET['editIncomeCategory' . $i] != $incomeName[$j]['category']) {
                $oldIncomeCategoryName = $incomeName[$j]['category'];
                $newIncomeCategoryName = $_GET['editIncomeCategory' . $i];
            }
            $j++;
        }


        $sql = "
                UPDATE `incomes_category_assigned_to_users` 
                SET category = :newIncomeCategoryName 
                WHERE category = :oldIncomeCategoryName AND user_id = :id
                ";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                         $id,                       PDO::PARAM_INT);
        $stmt->bindValue(':oldIncomeCategoryName',      $oldIncomeCategoryName,    PDO::PARAM_STR);
        $stmt->bindValue(':newIncomeCategoryName',      $newIncomeCategoryName,    PDO::PARAM_STR);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;

    }

    public static function selectIncomesCategory()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT incCat.category, incCat.id
                FROM incomes_category_assigned_to_users AS incCat 
                WHERE incCat.user_id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function updateIncomesDeletedCategory($deletedCategory)
    {
        $id = $_SESSION['id'];

        $inneCategory = $this->selectIdIncomesInneCategory();

        $inneID = 0;

        for($i = 0; $i < sizeof($inneCategory); $i++) {
            $inneID = $inneCategory[$i]['id'];
        }

        $sql = "
                UPDATE incomes 
                SET income_category_assigned_to_user_id = :inneID 
                WHERE incomes.user_id = :id AND income_category_assigned_to_user_id = :deletedCategory
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                     $id,                PDO::PARAM_INT);
        $stmt->bindValue(':inneID',                 $inneID,            PDO::PARAM_INT);
        $stmt->bindValue(':deletedCategory',        $deletedCategory,   PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    private function selectIdIncomesInneCategory()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT id
                FROM incomes_category_assigned_to_users AS incCat 
                WHERE incCat.user_id = :id AND category = 'Inne'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    private function selectIdDeletedIncomesCategory($deleteCategory)
    {
        $id = $_SESSION['id'];


        $sql = "
                SELECT id, category
                FROM incomes_category_assigned_to_users AS incCat 
                WHERE incCat.user_id = :id AND category = :deleteCategory
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id',                 $id,             PDO::PARAM_INT);
        $stmt->bindValue(':deleteCategory',     $deleteCategory, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}