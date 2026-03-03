<?php
namespace App\Security;

class Sanitizer
{
    public static function trimArray(array $in): array
    { return array_map(function ($v) { return is_string($v) ? trim($v) : $v; }, $in); }

    public static function email(?string $s): string
    { return filter_var((string)$s, FILTER_SANITIZE_EMAIL) ?: ''; }

    public static function string(?string $s, int $max = 255): string
    { $s = trim((string)$s); return mb_substr($s, 0, $max); }

    public static function int($v): int { return (int)$v; }
}