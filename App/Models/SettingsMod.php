<?php

namespace App\Models;

use PDO;

class SettingsMod extends \Core\Model
{
    public static function selectUsername()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT username 
                FROM users 
                WHERE id = $id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function selectEmail()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT email 
                FROM users
                WHERE id = $id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function deleteUserAccount()
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM users
                WHERE id='$id'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public static function selectLimit()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT name, expense_limit 
                FROM `expenses_category_assigned_to_users` 
                WHERE name='Higiena' AND user_id = '$id'
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}