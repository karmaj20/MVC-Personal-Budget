<?php

namespace App\Models;

use PDO;
use \App\Models\User;
use \App\Date;

class BalanceMod extends \Core\Model
{
    public static function getIncomeCurrentMonth()
    {
        return static::getBalanceIncomeSheet(Date::getBeginCurrentMonth(), Date::getEndCurrentMonth(), $_SESSION['id']);
    }

    public static function getExpenseCurrentMonth()
    {
        return static::getBalanceExpenseSheet(Date::getBeginCurrentMonth(), Date::getEndCurrentMonth(), $_SESSION['id']);
    }

    public static function getIncomePreviousMonth()
    {
        return static::getBalanceIncomeSheet(Date::getBeginPreviousMonth(), Date::getEndPreviousMonth(), $_SESSION['id']);
    }

    public static function getExpensePreviousMonth()
    {
        return static::getBalanceExpenseSheet(Date::getBeginPreviousMonth(), Date::getEndPreviousMonth(), $_SESSION['id']);
    }

    public static function getIncomeCurrentYear()
    {
        return static::getBalanceIncomeSheet(Date::getBeginCurrentYear(), Date::getEndCurrentYear(), $_SESSION['id']);
    }

    public static function getExpenseCurrentYear()
    {
        return static::getBalanceExpenseSheet(Date::getBeginCurrentYear(), Date::getEndCurrentYear(), $_SESSION['id']);
    }

    public static function getIncomeChosenPeriod()
    {
        if (isset($beginChosenPeriod)) {
            $beginChosenPeriod = $_POST['startPeriod'];
            $endChosenPeriod = $_POST['endPeriod'];

            return static::getBalanceIncomeSheet($beginChosenPeriod, $endChosenPeriod, $_SESSION['id']);
        }
        return 0;
    }

    public static function getExpenseChosenPeriod()
    {
        if (isset($beginChosenPeriod)) {
            $beginChosenPeriod = $_POST['startPeriod'];
            $endChosenPeriod = $_POST['endPeriod'];

            return static::getBalanceExpenseSheet($beginChosenPeriod, $endChosenPeriod, $_SESSION['id']);
        }

        return 0;
    }

    public static function getIncomeSumUpCurrentMonth()
    {
        return static::incomeSumUp(Date::getBeginCurrentMonth(), Date::getEndCurrentMonth(), $_SESSION['id']);
    }

    public static function getExpenseSumUpCurrentMonth()
    {
        return static::expenseSumUp(Date::getBeginCurrentMonth(), Date::getEndCurrentMonth(), $_SESSION['id']);
    }

    public static function getIncomeSumUpPreviousMonth()
    {
        return static::incomeSumUp(Date::getBeginPreviousMonth(), Date::getEndPreviousMonth(), $_SESSION['id']);
    }

    public static function getExpenseSumUpPreviousMonth()
    {
        return static::expenseSumUp(Date::getBeginPreviousMonth(), Date::getEndPreviousMonth(), $_SESSION['id']);
    }

    public static function getIncomeSumUpCurrentYear()
    {
        return static::incomeSumUp(Date::getBeginCurrentYear(), Date::getEndCurrentYear(), $_SESSION['id']);
    }

    public static function getExpenseSumUpCurrentYear()
    {
        return static::expenseSumUp(Date::getBeginCurrentYear(), Date::getEndCurrentYear(), $_SESSION['id']);
    }

    public static function getIncomeSumUpChosenPeriod()
    {
        if (isset($beginChosenPeriod)) {
            $beginChosenPeriod = $_POST['startPeriod'];
            $endChosenPeriod = $_POST['endPeriod'];
            return static::incomeSumUp($beginChosenPeriod, $endChosenPeriod, $_SESSION['id']);
        }
        return 0;
    }

    public static function getExpenseSumUpChosenPeriod()
    {
        if (isset($beginChosenPeriod)) {
            $beginChosenPeriod = $_POST['startPeriod'];
            $endChosenPeriod = $_POST['endPeriod'];

            return static::expenseSumUp($beginChosenPeriod, $endChosenPeriod, $_SESSION['id']);
        }

        return 0;
    }
}