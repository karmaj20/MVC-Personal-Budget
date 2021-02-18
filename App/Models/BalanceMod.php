<?php

namespace App\Models;

use PDO;

class BalanceMod extends \Core\Model
{
    public static function loadIncomeCurrentMonth()
    {
        $id = (int)$_SESSION['id'];

        $beginCurrentMonth = date('Y-m-01');
        $month = substr($beginCurrentMonth, 5,2);
        $day = substr($beginCurrentMonth, 6,2);
        $year = substr($beginCurrentMonth, 0,4);
        $endCurrentMonth = static::checkDateCorectness($month, $day, $year);

        return static::getBalanceIncomeSheet($beginCurrentMonth, $endCurrentMonth, $id);
    }

    public static function loadExpenseCurrentMonth()
    {
        $id = (int)$_SESSION['id'];

        $beginCurrentMonth = date('Y-m-01');
        $month = substr($beginCurrentMonth, 5,2);
        $day = substr($beginCurrentMonth, 6,2);
        $year = substr($beginCurrentMonth, 0,4);
        $endCurrentMonth = static::checkDateCorectness($month, $day, $year);

        return static::getBalanceExpenseSheet($beginCurrentMonth, $endCurrentMonth, $id);
    }

    public static function loadIncomePreviousMonth()
    {
        $id = (int)$_SESSION['id'];

        $beginPreviousMonth = date('Y-m-d', strtotime('first day of last month'));
        $endPreviousMonth =  date('Y-m-d', strtotime('last day of last month'));

        static::getBalanceIncomeSheet($beginPreviousMonth, $endPreviousMonth, $id);
    }

    public static function loadExpensePreviousMonth()
    {
        $id = (int)$_SESSION['id'];

        $beginPreviousMonth = date('Y-m-d', strtotime('first day of last month'));
        $endPreviousMonth =  date('Y-m-d', strtotime('last day of last month'));

        static::getBalanceExpenseSheet($beginPreviousMonth, $endPreviousMonth, $id);
    }


    private static function isLeapYear(int $year) : bool {

        if (($year % 4 == 0 && $year % 100 != 0) || ($year % 400 == 0)) {
            return true;
        } else {
            return false;
        }
    }

    private static function checkDateCorectness(string $month, string $day, string $year) {
        switch ($month) {
            case 1:
            case 3:
            case 5:
            case 7:
            case 8:
            case 10:
            case 12:
                $day = '31';
                break;
            case 4:
            case 6:
            case 9:
            case 11:
                $day = '30';
                break;
            case 2:
                if (static::isLeapYear((int)$year) == true) {
                    $day = '29';
                } else {
                    $day = '28';
                }
                break;
        }

        $date = $year . '-' . $month . '-' . $day;
        return $date;
    }

}