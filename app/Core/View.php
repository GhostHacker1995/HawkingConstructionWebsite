<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Renders a page view inside the site layout. Page views are
 * self-describing: they set $title, $description, $canonical,
 * $schema, etc. at the top, then output their <main> content.
 */
final class View
{
    /**
     * @param string $page  Path under app/Views, e.g. "pages/home"
     * @param array  $data  Dynamic data made available to the view
     */
    public static function render(string $page, array $data = [], int $status = 200): void
    {
        if ($status !== 200) {
            http_response_code($status);
        }

        // Shared defaults available to every view + layout
        $config       = require APP_PATH . '/Config/app.php';
        $assetVersion = $config['asset_version'] ?? '1';
        $base         = defined('BASE_URI') ? BASE_URI : '';

        // Absolute origin, derived from the live request so canonical and OG
        // URLs are correct in every environment (root or subfolder, http/https).
        $scheme  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? null) == 443) ? 'https' : 'http';
        $host    = $_SERVER['HTTP_HOST'] ?? parse_url($config['base_url'], PHP_URL_HOST);
        $baseUrl = $scheme . '://' . $host . $base;
        $ogImage = $baseUrl . '/assets/images/logos/png-logos/preview-og.png';

        // View-provided meta (overridable by the page file below)
        $title       = 'Hawkins Construction and General Supplies Ltd';
        $description = '';
        $canonical   = '/';
        $schema      = '';
        $headExtra   = '';
        $hasIntro    = false;
        $ogType      = 'website';

        extract($data, EXTR_OVERWRITE);

        // Capture the page content (the page file may also set meta vars)
        ob_start();
        require APP_PATH . '/Views/' . $page . '.php';
        $content = ob_get_clean();

        $canonicalUrl = $baseUrl . ($canonical === '/' ? '/' : '/' . ltrim($canonical, '/'));

        // Render the full layout into a buffer so we can apply the base path
        // to every root-relative link in one place.
        ob_start();
        require APP_PATH . '/Views/layouts/main.php';
        $html = ob_get_clean();

        echo self::applyBasePath($html, $base);
    }

    /**
     * Prefix the base path onto every root-relative href/src/action so the
     * site works in a subfolder. Protocol-relative (//) and absolute
     * (http://) URLs are left untouched. A no-op when running at root.
     */
    private static function applyBasePath(string $html, string $base): string
    {
        if ($base === '') {
            return $html;
        }
        return preg_replace(
            '#\b(href|src|action)="/(?!/)#',
            '$1="' . $base . '/',
            $html
        );
    }

    public static function renderError(int $code): void
    {
        $map = [404 => 'errors/404', 500 => 'errors/500'];
        $page = $map[$code] ?? 'errors/404';
        if (!is_file(APP_PATH . '/Views/' . $page . '.php')) {
            http_response_code($code);
            echo $code === 404 ? 'Page not found.' : 'Server error.';
            return;
        }
        self::render($page, [], $code);
    }
}
