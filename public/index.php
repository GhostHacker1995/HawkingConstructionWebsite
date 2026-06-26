<?php
/**
 * Hawkins Construction and General Supplies Ltd - Front Controller
 *
 * Every request that is not a real static file is routed here by
 * public/.htaccess. This file boots the application and dispatches
 * the request through the MVC router.
 */
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

/* ---------- PSR-4 style autoloader for the App\ namespace ---------- */
spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = APP_PATH . '/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

/* ---------- View helpers ---------- */
require APP_PATH . '/Core/helpers.php';

/* ---------- Configuration ---------- */
$config = require APP_PATH . '/Config/app.php';

/* ---------- Base path detection (works at domain root or in a subfolder) ----------
 * Examples of SCRIPT_NAME -> BASE_URI:
 *   /index.php                            -> ''                          (docroot = /public)
 *   /public/index.php                     -> ''                          (whole project at root)
 *   /HawkinsConstructionCompany/public/.. -> /HawkinsConstructionCompany (XAMPP subfolder)
 * Set 'base_path' in app/Config/app.php to override auto-detection.
 */
$basePath = $config['base_path'] ?? null;
if ($basePath === null) {
    $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
    $dir = rtrim($dir, '/');
    if (substr($dir, -7) === '/public') {
        $dir = substr($dir, 0, -7);
    }
    $basePath = $dir;
}
define('BASE_URI', $basePath === '/' ? '' : rtrim((string) $basePath, '/'));

/* ---------- Error handling ---------- */
if (!empty($config['debug'])) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

/* ---------- Secure session ---------- */
\App\Core\Session::start();

/* ---------- Dispatch ---------- */
$router = new \App\Core\Router($config);
$routes = require APP_PATH . '/Config/routes.php';
$routes($router);

try {
    $router->dispatch(
        $_SERVER['REQUEST_METHOD'] ?? 'GET',
        $_SERVER['REQUEST_URI'] ?? '/'
    );
} catch (\Throwable $e) {
    if (!empty($config['debug'])) {
        http_response_code(500);
        echo '<pre>' . htmlspecialchars($e->getMessage() . "\n" . $e->getTraceAsString()) . '</pre>';
    } else {
        \App\Core\View::renderError(500);
    }
}
