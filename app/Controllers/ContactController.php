<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Session;
use App\Models\ContactMessage;

final class ContactController extends Controller
{
    public function index(array $params = []): void
    {
        // Pull one-shot feedback set by submit() before the redirect.
        $this->view('pages/contact', [
            'flashStatus' => Session::flash('contact_status'),
            'flashErrors' => Session::flash('contact_errors') ?? [],
            'old'         => Session::flash('contact_old') ?? [],
        ]);
    }

    public function submit(array $params = []): void
    {
        $ajax = $this->isAjax();

        // 1. CSRF
        if (!Csrf::verify($this->request->raw('csrf_token'))) {
            $this->feedback($ajax, 'err', 'Your session has expired. Please reload the page and try again.');
            return;
        }

        // 2. Honeypot - bots fill this hidden field; humans never see it.
        if ($this->request->input('company_website') !== '') {
            // Pretend success so bots get no signal.
            $this->feedback($ajax, 'ok', 'Thank you. Your message has been sent.');
            return;
        }

        // 3. Validate + sanitize
        $message = new ContactMessage($this->request->all());
        if (!$message->validate()) {
            $this->feedback(
                $ajax,
                'err',
                'Please correct the highlighted fields and try again.',
                $message->errors,
                $message->data
            );
            return;
        }

        // 4. Deliver by SMTP
        $mailConfig = require APP_PATH . '/Config/mail.php';
        if ($message->send($mailConfig)) {
            $this->feedback($ajax, 'ok', 'Thank you. Your message has been sent. We reply within one business day.');
        } else {
            $this->feedback(
                $ajax,
                'err',
                'Sorry, the message could not be sent right now. Please email info@hawkinsconstruction.com or call +256 704 823 099.',
                [],
                $message->data
            );
        }
    }

    /** Was this request made by fetch/XHR (expects JSON)? */
    private function isAjax(): bool
    {
        $xrw    = strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '');
        $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
        return $xrw === 'xmlhttprequest' || strpos($accept, 'application/json') !== false;
    }

    /**
     * Return feedback to the user. AJAX requests get JSON (immediate,
     * inline). Classic requests use Post/Redirect/Get with flash messages.
     */
    private function feedback(bool $ajax, string $type, string $text, array $errors = [], array $old = []): void
    {
        if ($ajax) {
            header('Content-Type: application/json; charset=UTF-8');
            echo json_encode([
                'ok'      => $type === 'ok',
                'message' => $text,
                'errors'  => $errors,
            ]);
            exit;
        }

        Session::flash('contact_status', ['type' => $type, 'text' => $text]);
        if ($errors) {
            Session::flash('contact_errors', $errors);
        }
        if ($old) {
            Session::flash('contact_old', $old);
        }
        $this->redirect('/contact#contact-form');
    }
}
