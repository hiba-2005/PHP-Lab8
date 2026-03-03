<?php
namespace App\Security;

class Validator
{
    public static function cne(string $s): bool
    { return (bool)preg_match('/^[A-Z0-9]{6,20}$/', $s); }

    public static function maxLen(string $s, int $max): bool
    { return mb_strlen($s) <= $max; }

    public static function email(string $s): bool
    { return (bool)filter_var($s, FILTER_VALIDATE_EMAIL); }
}
