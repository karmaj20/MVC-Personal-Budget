<?php

namespace App\Models;

use App\Token;
use Core\View;
use PDO;
// use App\Mail;

class User extends \Core\Model
{
    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public function saveUserToDatabase() : bool
    {
        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->userPassword, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();

            $sql = "INSERT INTO users
                    VALUES (null, :username, :password, :email)";

            $database = static::getDatabase();
            $stmt = $database->prepare($sql);

            $stmt->bindValue(':username', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function validate() : void
    {
        // name
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid email';
        }

        if (static::emailExists($this->email, $this->id ?? null)) {
            $this->errors[] = 'email already taken';
        }

        // password
        if (isset($this->userPassword)) {

            if (strlen($this->userPassword) < 6) {
                $this->errors[] = 'Hasło musi zawierać co najmniej 6 znaków';
            }

            if (preg_match('/.*[a-z]+.*/i', $this->userPassword) == 0) {
                $this->errors[] = 'Hasło musi zawierać co najmniej 1 litere';
            }

            if (preg_match('/.*\d+.*/i', $this->userPassword) == 0) {
                $this->errors[] = 'Hasło musi zawierać co najmniej jedną cyfrę';
            }
        }
    }

    public static function emailExists(string $email, $ignore_id = null) : bool
    {
        $user = static::findByUsername($email);

        if ($user) {
            if ($user->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    public static function findByUsername(string $username)
    {
        $sql = 'SELECT * FROM users WHERE username = :username';

        $database = static::getDatabase();
        $stmt = $database->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $database = static::getDatabase();
        $stmt = $database->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate(string $name, string $password)
    {
        $user = static::findByUsername($name);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }


}