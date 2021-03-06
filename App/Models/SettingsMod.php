<?php

namespace App\Models;

use App\Flash;
use PDO;

class SettingsMod extends \Core\Model
{
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

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

    public function deleteUserAccount()
    {
        $id = $_SESSION['id'];

        $sql = "
                DELETE FROM users
                WHERE id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $result = $stmt->fetchAll();
        $stmt->execute();

        return $result;
    }

    public function updateUserAccount()
    {
        $validate = new User($_GET);

        try {
            $validate->validate();

            if (empty($this->errors)) {

                $id = $_SESSION['id'];
                $username = $_GET['name'];
                $email = $_GET['email'];

                $sql = "
                        UPDATE users
                        SET username = '$username', email = '$email'
                        WHERE id = :id
                        ";

                $db = static::getDB();
                $stmt = $db->prepare($sql);

                $stmt->bindValue(':id', $id, PDO::PARAM_INT);

                $result = $stmt->fetchAll();
                $stmt->execute();

                Flash::addMessage('Dane zostały zmienione.', FLASH::SUCCESS);

                return $result;
            }
        } catch (\Exception $exception) {

            Flash::addMessage("Email jest przypisany do innego konta, spróbuj innego.", FLASH::INFO);
        }

    }

    public function editPassword()
    {
        $id = $_SESSION['id'];

        $currentPasswordFromDB = $this->selectPassword();
        $currentPassword        = $_GET['password'];
        $newPassword            = $_GET['userPassword'];
        $confirmNewPassword     = $_GET['repeatedUserPassword'];
        $newVar = $currentPasswordFromDB[0][0];



        if (password_verify($currentPassword, $newVar) && $newPassword == $confirmNewPassword) {

            $newPassword_hash = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "
                UPDATE users 
                SET password = '$newPassword_hash'  
                WHERE id = :id
                ";

            $db = static::getDb();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            $result = $stmt->fetchAll();
            $stmt->execute();

            return true;
        }

        return false;

    }

    public static function convertTextToFirstCapitalize($word)
    {
        $word = strtolower($word);
        $word = ucwords($word);

        return $word;
    }

    private static function selectPassword()
    {
        $id = $_SESSION['id'];

        $sql = "
                SELECT password 
                FROM users 
                WHERE id = :id
                ";

        $db = static::getDb();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}