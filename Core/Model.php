<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
 * PHP version 7.0
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }

    protected static function getBalanceIncomeSheet($start, $end, $id)
    {
        $sql = "  
                SELECT incdef.name, SUM(inc.ammount) 
                FROM incomes AS inc 
                INNER JOIN income_category_default AS incdef 
                ON inc.income_category_assigned_to_user_id = incdef.id 
                INNER JOIN users 
                ON inc.user_id = users.id AND inc.user_id = '$id'
                WHERE date_of_income BETWEEN '$start' AND '$end'
                GROUP BY income_category_assigned_to_user_id
               ";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected static function getBalanceExpenseSheet($start, $end, $id)
    {
        $sql = "  
                SELECT exdef.name, SUM(ex.ammount) 
                FROM expenses AS ex 
                INNER JOIN expenses_category_default AS exdef 
                ON ex.expense_category_assigned_to_user_id = exdef.id
                INNER JOIN users 
                ON ex.user_id = users.id AND ex.user_id = '$id'
                WHERE date_of_expense BETWEEN '$start' AND '$end'
                GROUP BY expense_category_assigned_to_user_id
               ";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected static function incomeSumUp($start, $end, $id)
    {
        $sql = "
                SELECT SUM(ammount) FROM incomes AS inc 
                INNER JOIN users ON inc.user_id = users.id AND inc.user_id = '$id'
                WHERE date_of_income BETWEEN '$start' AND '$end'
                ";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected static function expenseSumUp($start, $end, $id)
    {
        $sql = "
                SELECT SUM(ammount) FROM expenses AS exp
                INNER JOIN users ON exp.user_id = users.id AND exp.user_id = '$id'
                WHERE date_of_expense BETWEEN '$start' AND '$end'
                ";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    protected static function detailedIncomeBalance($start, $end, $id)
    {
        $sql = "
                SELECT incdef.name, inc.ammount, inc.date_of_income, inc.income_comment 
                FROM incomes AS inc 
                INNER JOIN income_category_default AS incdef 
                ON inc.income_category_assigned_to_user_id = incdef.id 
                INNER JOIN users 
                ON inc.user_id = users.id AND inc.user_id = '$id'
                WHERE date_of_income BETWEEN '$start' AND '$end'
				ORDER BY date_of_income ASC
			    ";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->execute();

//        dump($stmt->fetchAll());
        return $stmt->fetchAll();
    }

    protected static function detailedExpenseBalance($start, $end, $id)
    {
        $sql = "
                SELECT exdef.name, ex.ammount, ex.date_of_expense, ex.expense_comment 
                FROM expenses AS ex 
                INNER JOIN expenses_category_default AS exdef 
                ON ex.expense_category_assigned_to_user_id = exdef.id 
                INNER JOIN users 
                ON ex.user_id = users.id AND ex.user_id = '$id'
                WHERE date_of_expense BETWEEN '$start' AND '$end'
				ORDER BY date_of_expense ASC
			    ";

        $db = static::getDB();

        $stmt = $db->prepare($sql);
        $stmt->execute();

//        dump($stmt->fetchAll());
        return $stmt->fetchAll();
    }
}
