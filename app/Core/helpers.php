<?php
declare(strict_types=1);

/**
 * Global view helpers. Loaded once during bootstrap.
 */

if (!function_exists('e')) {
    /** HTML-escape for safe output. */
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('app_base')) {
    /** Application URL base path ('' at root, '/subfolder' otherwise). */
    function app_base(): string
    {
        return defined('BASE_URI') ? BASE_URI : '';
    }
}

if (!function_exists('url')) {
    /** Build an internal URL with the base path applied. */
    function url(string $path = '/'): string
    {
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . $path;
        }
        return app_base() . $path;
    }
}

if (!function_exists('asset')) {
    /** Build an asset URL with the base path applied. */
    function asset(string $path): string
    {
        return url('/' . ltrim($path, '/'));
    }
}

if (!function_exists('current_path')) {
    /** Normalized current request path (base stripped, no trailing slash). */
    function current_path(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        $base = app_base();
        if ($base !== '' && strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
            if ($path === '') {
                $path = '/';
            }
        }
        return $path === '/' ? '/' : rtrim($path, '/');
    }
}

if (!function_exists('nav_items')) {
    /** Primary navigation items (clean URLs). */
    function nav_items(): array
    {
        return [
            ['href' => '/',           'label' => 'Home'],
            ['href' => '/about',      'label' => 'About'],
            ['href' => '/services',   'label' => 'Services'],
            ['href' => '/projects',   'label' => 'Projects'],
            ['href' => '/hse',        'label' => 'HSE'],
            ['href' => '/leadership', 'label' => 'Leadership'],
            ['href' => '/contact',    'label' => 'Contact'],
        ];
    }
}

if (!function_exists('nav_active')) {
    /** Is the given nav href the current section? */
    function nav_active(string $href, string $current): bool
    {
        if ($href === '/') {
            return $current === '/';
        }
        return $current === $href || strpos($current, $href . '/') === 0;
    }
}

if (!function_exists('company')) {
    /**
     * Single source of truth for company contact details. Centralizing this
     * here makes the future move to a database-backed CMS a one-file change.
     */
    function company(): array
    {
        return [
            'name'      => 'Hawkins Construction and General Supplies Ltd',
            'short'     => 'Hawkins Construction',
            'tagline'   => 'Building excellence. Delivering confidence.',
            'email'     => 'info@hawkinsconstruction.com',
            'phones'    => ['+256 775 718 929', '+256 704 823 099', '+256 788 137 549', '+256 752 036 122'],
            'whatsapp'  => '256704823099',
            'address'   => 'Wampewo, Kasangati Town Council, Wakiso District, Uganda',
        ];
    }
}
