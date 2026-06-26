<?php
declare(strict_types=1);

namespace App\Core;

/**
 * CSRF protection. A per-session token is embedded as a hidden field
 * in every form and verified on POST.
 */
final class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::KEY];
    }

    public static function field(): string
    {
        return '<input type="hidden" name="csrf_token" value="'
            . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(?string $token): bool
    {
        $stored = $_SESSION[self::KEY] ?? '';
        return is_string($token) && $stored !== '' && hash_equals($stored, $token);
    }
}
