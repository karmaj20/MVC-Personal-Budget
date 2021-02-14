<?php

declare(strict_types = 1);

namespace App;

class Token
{
    protected string $token;

    public function __construct($token_value = null)
    {
        if ($token_value) {
            $this->token = $token_value;
        }

        try {
            $this->token = bin2hex(random_bytes(16));
        } catch (\Exception $e) {
            echo 'Brak';
        }
    }

    public function getValue() : string
    {
        return $this->token;
    }

    public function getHash() : string
    {
        return hash_hmac('sha256', $this->token, \App\Config::SECRET_KEY);
    }
}