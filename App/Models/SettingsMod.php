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
}