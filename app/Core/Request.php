<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Lightweight request accessor with built-in input sanitization.
 */
final class Request
{
    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    public function uri(): string
    {
        return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    }

    /** Raw (untrimmed) POST value. */
    public function raw(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /** Trimmed, control-character-stripped POST value. */
    public function input(string $key, string $default = ''): string
    {
        $value = $_POST[$key] ?? $default;
        if (!is_string($value)) {
            return $default;
        }
        // Strip null bytes and trim surrounding whitespace
        return trim(str_replace("\0", '', $value));
    }

    public function all(): array
    {
        return $_POST;
    }
}
