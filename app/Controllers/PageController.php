<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;

/**
 * Static-content pages that need no extra logic. Each maps to a
 * view under app/Views/pages. When the CMS arrives, these actions
 * are where you load the page's content from the database.
 */
final class PageController extends Controller
{
    public function about(array $params = []): void
    {
        $this->view('pages/about');
    }

    public function services(array $params = []): void
    {
        $this->view('pages/services');
    }

    public function projects(array $params = []): void
    {
        $this->view('pages/projects');
    }

    public function hse(array $params = []): void
    {
        $this->view('pages/hse');
    }

    public function leadership(array $params = []): void
    {
        $this->view('pages/leadership');
    }
}
