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
        return static::getBalanceIncomeSheet(Date::getBeginChosenPeriod(), Date::getEndChosenPeriod(), $_SESSION['id']);
    }

    public static function getExpenseChosenPeriod()
    {
        return static::getBalanceExpenseSheet(Date::getBeginChosenPeriod(), Date::getEndChosenPeriod(), $_SESSION['id']);
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
        return static::incomeSumUp(Date::getBeginChosenPeriod(), Date::getEndChosenPeriod(), $_SESSION['id']);
    }

    public static function getExpenseSumUpChosenPeriod()
    {
        return static::expenseSumUp(Date::getBeginChosenPeriod(), Date::getEndChosenPeriod(), $_SESSION['id']);
    }

    public static function getDetailedIncomeCurrentMonth()
    {
        return static::detailedIncomeBalance(Date::getBeginCurrentMonth(), Date::getEndCurrentMonth(), $_SESSION['id']);
    }

    public static function getDetailedExpenseCurrentMonth()
    {
        return static::detailedExpenseBalance(Date::getBeginCurrentMonth(), Date::getEndCurrentMonth(), $_SESSION['id']);
    }

    public static function getDetailedIncomePreviousMonth()
    {
        return static::detailedIncomeBalance(Date::getBeginPreviousMonth(), Date::getEndPreviousMonth(), $_SESSION['id']);
    }

    public static function getDetailedExpensePreviousMonth()
    {
        return static::detailedExpenseBalance(Date::getBeginPreviousMonth(), Date::getEndPreviousMonth(), $_SESSION['id']);
    }

    public static function getDetailedIncomeCurrentYear()
    {
        return static::detailedIncomeBalance(Date::getBeginCurrentYear(), Date::getEndCurrentYear(), $_SESSION['id']);
    }

    public static function getDetailedExpenseCurrentYear()
    {
        return static::detailedExpenseBalance(Date::getBeginCurrentYear(), Date::getEndCurrentYear(), $_SESSION['id']);
    }

    public static function getDetailedIncomeChosenPeriod()
    {
        return static::detailedIncomeBalance(Date::getBeginChosenPeriod(), Date::getEndChosenPeriod(), $_SESSION['id']);
    }

    public static function getDetailedExpenseChosenPeriod()
    {
        return static::detailedExpenseBalance(Date::getBeginChosenPeriod(), Date::getEndChosenPeriod(), $_SESSION['id']);
    }
}