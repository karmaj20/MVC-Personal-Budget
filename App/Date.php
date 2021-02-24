<?php

namespace App;

class Date
{
    public static function getCurrentDate()
    {
        return date('Y-m-d');
    }

    public static function getBeginCurrentMonth()
    {
        return date('Y-m-01');
    }

    public static function getEndCurrentMonth()
    {
        $month = static::getMonth();
        $day = static::getDay();
        $year = static::getYear();
        return static::checkDateCorectness($month, $day, $year);
    }

    public static function getBeginPreviousMonth()
    {
        return date('Y-m-d', strtotime('first day of last month'));
    }

    public static function getEndPreviousMonth()
    {
        return date('Y-m-d', strtotime('last day of last month'));
    }

    public static function getBeginCurrentYear()
    {
        return date('Y-01-01');
    }

    public static function getEndCurrentYear()
    {
        return date('Y-12-31');
    }

    public static function getBeginChosenPeriod()
    {
        if (isset($_POST['startPeriod'])) {
            return $_POST['startPeriod'];
        } else {
            return 0;
        }
    }

    public static function getEndChosenPeriod()
    {
        if (isset($_POST['endPeriod'])) {
            return $_POST['endPeriod'];
        } else {
            return 0;
        }
    }

    public static function getRange()
    {
        if(isset($_POST['update'])){
            if(!empty($_POST['range'])) {
                return $_POST['range'];
            }
        }
    }

    private static function getMonth()
    {
        return substr(static::getBeginCurrentMonth(), 5,2);
    }

    private static function getDay()
    {
        return substr(static::getBeginCurrentMonth(), 6,2);
    }

    private static function getYear()
    {
        return substr(static::getBeginCurrentMonth(), 0,4);
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