<?php
/**
 * Application configuration (environment-aware).
 *
 * The app auto-detects whether it is running locally or in production
 * from the request host, and auto-detects the URL base path so it works
 * at a domain root (cPanel public_html) or in a subfolder (XAMPP).
 * Override anything explicitly below if you need to.
 */

$host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? 'localhost'));
$isLocal =
    $host === 'localhost'
    || strpos($host, 'localhost:') === 0
    || strpos($host, '127.0.0.1') === 0
    || substr($host, -6) === '.local'
    || substr($host, -5) === '.test';

return [
    // 'local' or 'production' - derived from the request host.
    'env' => $isLocal ? 'local' : 'production',

    // Show full error traces locally; stay silent in production.
    'debug' => $isLocal,

    // URL base path. null = auto-detect (recommended). Set to '' to force a
    // domain root, or '/subfolder' to force a specific prefix.
    'base_path' => null,

    // Canonical production origin (used as a fallback for canonical/OG URLs).
    // Live canonical/OG URLs are built automatically from the request host.
    'base_url' => 'https://hawkinsconstruction.com',

    // Bump after editing CSS/JS to force browsers to reload cached assets.
    'asset_version' => '1',
];
