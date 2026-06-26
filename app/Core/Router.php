<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Centralized router. Maps clean URLs to "Controller@action".
 * Supports named parameters, e.g. /services/{slug}.
 */
final class Router
{
    /** @var array<string, array<string, array{handler:string, params:array}>> */
    private array $routes = ['GET' => [], 'POST' => []];
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function get(string $path, string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, string $handler): void
    {
        $this->routes[$method][$this->normalize($path)] = $handler;
    }

    private function normalize(string $path): string
    {
        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        $path = '/' . trim($path, '/');
        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public function dispatch(string $method, string $uri): void
    {
        $method = strtoupper($method);
        if ($method === 'HEAD') {
            $method = 'GET';
        }
        $path = $this->normalize($uri);

        // Strip the application base path so routes are matched against the
        // clean path (e.g. /GrayhostWebsiteNew/about -> /about).
        if (defined('BASE_URI') && BASE_URI !== '' && strpos($path, BASE_URI) === 0) {
            $path = substr($path, strlen(BASE_URI));
            if ($path === '') {
                $path = '/';
            }
        }

        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $params = $this->match($route, $path);
            if ($params !== null) {
                $this->invoke($handler, $params);
                return;
            }
        }

        // No match -> 404
        View::renderError(404);
    }

    /**
     * Returns captured params if the route matches, otherwise null.
     * @return array<string,string>|null
     */
    private function match(string $route, string $path): ?array
    {
        if (strpos($route, '{') === false) {
            return $route === $path ? [] : null;
        }
        $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[a-zA-Z0-9\-]+)', $route);
        $pattern = '#^' . $pattern . '$#';
        if (preg_match($pattern, $path, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }
        return null;
    }

    private function invoke(string $handler, array $params): void
    {
        [$controller, $action] = explode('@', $handler);
        $class = 'App\\Controllers\\' . $controller;

        if (!class_exists($class)) {
            View::renderError(404);
            return;
        }
        $instance = new $class($this->config);
        if (!method_exists($instance, $action)) {
            View::renderError(404);
            return;
        }
        $instance->{$action}($params);
    }
}
