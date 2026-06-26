<?php
/**
 * Local development router for PHP's built-in server only.
 * NOT used in production (Apache + .htaccess handle routing there).
 *
 *   php -S 127.0.0.1:8820 -t public server.php
 */
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/');
$public = __DIR__ . '/public';

// Serve real static files (assets, fonts, images) directly.
if ($uri !== '/' && is_file($public . $uri)) {
    return false;
}

// Everything else goes through the front controller.
require $public . '/index.php';
