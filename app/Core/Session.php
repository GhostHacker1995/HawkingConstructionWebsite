<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Thin wrapper around PHP sessions with secure defaults and
 * one-shot "flash" messages used for form feedback after redirects.
 */
final class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        // Use a guaranteed-writable session directory inside the project.
        // The default host path is sometimes not writable on shared hosting,
        // which silently drops sessions (and breaks CSRF + flash feedback).
        if (defined('BASE_PATH')) {
            $dir = BASE_PATH . '/storage/sessions';
            if (!is_dir($dir)) {
                @mkdir($dir, 0700, true);
            }
            if (is_dir($dir) && is_writable($dir)) {
                session_save_path($dir);
            }
        }

        // Detect HTTPS, including behind a reverse proxy / load balancer.
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? null) == 443)
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

        session_set_cookie_params([
            'lifetime' => 0,
            'path'     => '/',
            'secure'   => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_name('HAWKINSSESS');
        @session_start();
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /** Store a value that survives exactly one redirect, then disappears. */
    public static function flash(string $key, $value = null)
    {
        if ($value === null) {
            $val = $_SESSION['_flash'][$key] ?? null;
            unset($_SESSION['_flash'][$key]);
            return $val;
        }
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
}
