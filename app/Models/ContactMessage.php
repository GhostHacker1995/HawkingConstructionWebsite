<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Mailer;
use App\Core\Validator;

/**
 * Validates a contact / inquiry submission and delivers it by email.
 */
final class ContactMessage
{
    /** @var array<string,string> */
    public array $errors = [];
    /** @var array<string,string> */
    public array $data = [];

    public function __construct(array $input)
    {
        $this->data = [
            'name'    => $this->clean($input['name']    ?? ''),
            'email'   => $this->clean($input['email']   ?? ''),
            'phone'   => $this->clean($input['phone']   ?? ''),
            'service' => $this->clean($input['subject'] ?? ($input['service'] ?? '')),
            'message' => $this->clean($input['message'] ?? '', true),
            'source'  => $this->clean($input['source']  ?? 'Contact form'),
        ];
    }

    public function validate(): bool
    {
        $v = new Validator($this->data);
        $v->required('name', 'Please enter your name.')
          ->max('name', 120, 'That name looks too long.')
          ->required('email', 'Please enter your email address.')
          ->email('email', 'Please enter a valid email address.')
          ->phone('phone', 'Please enter a valid phone number.')
          ->required('message', 'Please include a short message.')
          ->max('message', 5000, 'Your message is too long.');

        $this->errors = $v->errors();
        return $v->passes();
    }

    public function send(array $mailConfig): bool
    {
        $mailer = new Mailer($mailConfig);

        $subject = 'New website inquiry: ' . ($this->data['service'] ?: 'General enquiry');

        $lines = [
            'A new message was submitted on hawkinsconstruction.com',
            str_repeat('-', 48),
            'Name:    ' . $this->data['name'],
            'Email:   ' . $this->data['email'],
            'Phone:   ' . ($this->data['phone'] !== '' ? $this->data['phone'] : '-'),
        ];
        if ($this->data['service'] !== '') {
            $lines[] = 'Service: ' . $this->data['service'];
        }
        $lines[] = 'Source:  ' . $this->data['source'];
        $lines[] = '';
        $lines[] = 'Message:';
        $lines[] = $this->data['message'] !== '' ? $this->data['message'] : '(no message provided)';
        $lines[] = '';
        $lines[] = str_repeat('-', 48);
        $lines[] = 'Sent ' . date('D, d M Y H:i') . ' (server time)';

        $body = implode("\n", $lines);

        $ok = $mailer->send($subject, $body, $this->data['email'], $this->data['name']);
        if (!$ok) {
            $this->errors['_mail'] = $mailer->lastError();
        }
        return $ok;
    }

    /**
     * Sanitize input: strip tags and control chars. Messages keep
     * line breaks; single-line fields are flattened.
     */
    private function clean(string $value, bool $multiline = false): string
    {
        $value = str_replace("\0", '', $value);
        $value = strip_tags($value);
        if (!$multiline) {
            $value = preg_replace('/[\r\n]+/', ' ', $value);
        }
        return trim($value);
    }
}
