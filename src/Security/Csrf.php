<?php
namespace App\Security;

class Csrf
{
    private $key = 'csrf_token';

    public function token(): string
    {
        if (empty($_SESSION[$this->key])) {
            $_SESSION[$this->key] = bin2hex(random_bytes(32));
        }
        return $_SESSION[$this->key];
    }

    public function verify(?string $token): bool
    {
        if (!$token || empty($_SESSION[$this->key])) { return false; }
        return hash_equals($_SESSION[$this->key], $token);
    }
}
