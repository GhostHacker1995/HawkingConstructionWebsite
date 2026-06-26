<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Base controller. Gives every controller a request object and
 * convenience helpers for rendering views and redirecting.
 */
abstract class Controller
{
    protected array $config;
    protected Request $request;

    public function __construct(array $config = [])
    {
        $this->config  = $config;
        $this->request = new Request();
    }

    protected function view(string $page, array $data = [], int $status = 200): void
    {
        View::render($page, $data, $status);
    }

    protected function redirect(string $path): void
    {
        // Prefix internal paths with the app base so redirects work in a subfolder.
        if ($path !== '' && $path[0] === '/' && defined('BASE_URI') && BASE_URI !== '') {
            $path = BASE_URI . $path;
        }
        header('Location: ' . $path, true, 302);
        exit;
    }

    protected function notFound(): void
    {
        View::renderError(404);
    }

    /** Escape helper for safe output in views. */
    public static function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}
