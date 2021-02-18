<?php

namespace App\Models;

use App\Token;
use Core\View;
use PDO;
use App\Mail;

/**
 * User model
 *
 * PHP version 7.0
 */
class User extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];
    public $keepPaymentMethodsDefault = array  ('Gotówka', 'Karta', 'Przelew', 'Inna forma płatności');
    public $keepIncomeCategoryDefault = array  ('Wynagrodzenie', 'Odsetki Bankowe', 'Sprzedaż na allegro', 'Inne przychody');
    public $keepExpenseCategoryDefault = array ('Jedzenie', 'Mieszkanie', 'Transport', 'Telekomunikacja', 'Opieka zdrowotna',
                                                'Ubranie', 'Higiena', 'Dzieci', 'Rozrywka', 'Wycieczka', 'Szkolenia', 'Książki',
                                                'Oszczędności', 'Emerytura', 'Spłata długów', 'Darowizna', 'Inne wydatki');

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $password_hash = password_hash($this->userPassword, PASSWORD_DEFAULT);

            $token = new Token();
            $hashed_token = $token->getHash();
            $this->activation_token = $token->getValue();

            $sql = 'INSERT INTO users
                    VALUES (null, :username, :password, :email)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':username', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Name
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

        // Password
        if (isset($this->userPassword)) {

            if ($this->userPassword != $this->repeatedUserPassword) {
                $this->errors[] = 'Password must match confirmation.';
            }

            if (strlen($this->userPassword) < 6) {
                $this->errors[] = 'Please enter at least 6 characters for the password';
            }

            if (preg_match('/.*[a-z]+.*/i', $this->userPassword) == 0) {
                $this->errors[] = 'Password needs at least one letter';
            }

            if (preg_match('/.*\d+.*/i', $this->userPassword) == 0) {
                $this->errors[] = 'Password needs at least one number';
            }
        }
    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email, $ignore_id = null)
    {
        $user = static::findByEmail($email);

        if($user) {
            if($user->id != $ignore_id){
                return true;
            }
        }

        return false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if($user/* && $user->is_active*/){
            if(password_verify($password, $user->password)){
                return $user;
            }
        }

        return false;
    }

    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30; // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function addPaymentMethodsToUser()
    {
        $id = $this->getAlreadyRegistredUserID();

        $sql = "
                INSERT INTO payment_methods_assigned_to_users 
                VALUES (NULL, :id, :keepPaymentMethodsDefault[0]), (NULL, :id, :keepPaymentMethodsDefault[1]),
                       (NULL, :id, :keepPaymentMethodsDefault[2]), (NULL, :id, :keepPaymentMethodsDefault[3])
                ";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        for ($i = 0; $i < 4; $i++) {
            $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':keepPaymentMethodsDefault[$i]', $this->keepPaymentMethodsDefault[$i], PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function addIncomeCategoryDefaultToUser()
    {
        $id = $this->getAlreadyRegistredUserID();

        $sql = "
                INSERT INTO incomes_category_assigned_to_users 
                VALUES (NULL, :id, :keepIncomeCategoryDefault[0]), (NULL, :id, :keepIncomeCategoryDefault[1]),
                       (NULL, :id, :keepIncomeCategoryDefault[2]), (NULL, :id, :keepIncomeCategoryDefault[3])
                ";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        for ($i = 0; $i < 4; $i++) {
            $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':keepIncomeCategoryDefault[$i]', $this->keepIncomeCategoryDefault[$i], PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    public function addExpenseCategoryDefaultToUser()
    {
        $id = $this->getAlreadyRegistredUserID();

        $sql = ("
                INSERT INTO expenses_category_assigned_to_users 
                VALUES (NULL, :id, :keepExpenseCategoryDefault[0]),  (NULL, :id, :keepExpenseCategoryDefault[1]),
                       (NULL, :id, :keepExpenseCategoryDefault[2]),  (NULL, :id, :keepExpenseCategoryDefault[3]),
                       (NULL, :id, :keepExpenseCategoryDefault[4]),  (NULL, :id, :keepExpenseCategoryDefault[5]),
                       (NULL, :id, :keepExpenseCategoryDefault[6]),  (NULL, :id, :keepExpenseCategoryDefault[7]),
                       (NULL, :id, :keepExpenseCategoryDefault[8]),  (NULL, :id, :keepExpenseCategoryDefault[9]),
                       (NULL, :id, :keepExpenseCategoryDefault[10]), (NULL, :id, :keepExpenseCategoryDefault[11]),
                       (NULL, :id, :keepExpenseCategoryDefault[12]), (NULL, :id, :keepExpenseCategoryDefault[13]),
                       (NULL, :id, :keepExpenseCategoryDefault[14]), (NULL, :id, :keepExpenseCategoryDefault[15]),
                       (NULL, :id, :keepExpenseCategoryDefault[16])
                ");

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        for ($i = 0; $i < 17; $i++) {
            $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':keepIncomeCategoryDefault[$i]', $this->keepIncomeCategoryDefault[$i], PDO::PARAM_STR);
        }

        return $stmt->execute();
    }

    private function getAlreadyRegistredUserID()
    {
        $sql = "SELECT id FROM users ORDER BY id DESC LIMIT 1";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
    /*
    public static function sendPasswordReset($email)
    {
        $user = static::findByEmail($email);

        if ($user) {

            if($user->startPasswordReset()){

                $user->sendPasswordResetEmail();

            }

        }
    }

    protected function startPasswordReset()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->password_reset_token = $token->getValue();

        $expiry_timestamp = time() + 60 * 60 * 2;

        $sql = 'UPDATE users
                SET password_reset_hash = :token_hash,
                    password_reset_expires_at = :expires_at
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $expiry_timestamp), PDO::PARAM_STR);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    protected function sendPasswordResetEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/password/reset/' . $this->password_reset_token;

        $text = View::getTemplate('Password\reset_email.txt', ['url' => $url]);
        $html = View::getTemplate('Password\reset_email.html', ['url' => $url]);

        Mail::send($this->email, 'Password reset', $text, $html);
    }

    public static function findByPasswordReset($token)
    {
        $token = new Token($token);
        $hashed_token = $token->getHash();

        $sql = 'SELECT * FROM users
                WHERE password_reset_hash = :token_hash';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $user = $stmt->fetch();

        if (strtotime($user->password_reset_expires_at) > time()){

            return $user;

        }
    }

    public function resetPassword($password)
    {
        $this->password = $password;

        $this->validate();

        if(empty($this->errors)){

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'UPDATE users
                    SET password_hash = :password_hash,
                        password_reset_hash = NULL,
                        password_reset_expires_at = NULL
                    WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function sendActivationEmail()
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . '/signup/activate/' . $this->activation_token;

        $text = View::getTemplate('Signup\activation_email.txt', ['url' => $url]);
        $html = View::getTemplate('Signup\activation_email.html', ['url' => $url]);

        Mail::send($this->email, 'Account activation', $text, $html);
    }

    public static function activate($value)
    {
        $token = new Token($value);
        $hashed_token = $token->getHash();

        $sql = 'UPDATE users
                SET is_active = 1,
                    activation_hash = null 
                WHERE activation_hash = :hashed_token';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':hashed_token', $hashed_token, PDO::PARAM_STR);

        $stmt->execute();
    }

    public function updateProfile($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];

        $this->validate();

        if (empty($this->errors)) {

            $sql = 'UPDATE users
                    SET name = :name,
                        email = :email';

            if(isset($this->password)) {
                $sql .= ', password_hash = :password_hash';
            }

            $sql .= "\nWHERE id = :id";

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            if (isset($this->password)) {
                $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);
            }
            return $stmt->execute();
        }

        return false;
    }
    */
}
