<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Dependency-free SMTP mailer for shared hosting.
 *
 * Supports implicit SSL (port 465), STARTTLS (port 587) and plain
 * connections, with AUTH LOGIN. Configure everything in
 * app/Config/mail.php — no Composer or extensions required.
 */
final class Mailer
{
    private array $cfg;
    private string $error = '';

    public function __construct(array $config)
    {
        $this->cfg = $config;
    }

    public function lastError(): string
    {
        return $this->error;
    }

    /**
     * Send a plain-text email. Returns true on success.
     */
    public function send(string $subject, string $body, ?string $replyTo = null, ?string $replyName = null): bool
    {
        $host       = $this->cfg['host'] ?? '';
        $port       = (int) ($this->cfg['port'] ?? 587);
        $encryption = strtolower((string) ($this->cfg['encryption'] ?? 'tls'));
        $username   = $this->cfg['username'] ?? '';
        $password   = $this->cfg['password'] ?? '';
        $fromEmail  = $this->cfg['from_email'] ?? $username;
        $fromName   = $this->cfg['from_name'] ?? 'Grayhost';
        $toEmail    = $this->cfg['to_email'] ?? '';
        $toName     = $this->cfg['to_name'] ?? '';
        $timeout    = (int) ($this->cfg['timeout'] ?? 20);

        if ($host === '' || $toEmail === '') {
            $this->error = 'Mail is not configured.';
            $this->log('FAIL: mail not configured (host/to_email missing).');
            return false;
        }

        $verifyPeer = (bool) ($this->cfg['verify_peer'] ?? false);
        $transport  = $encryption === 'ssl' ? 'ssl://' . $host : $host;

        $context = stream_context_create([
            'ssl' => [
                'verify_peer'       => $verifyPeer,
                'verify_peer_name'  => $verifyPeer,
                'allow_self_signed' => !$verifyPeer,
            ],
        ]);

        $this->log("Connecting to {$transport}:{$port} (encryption={$encryption}, verify_peer=" . ($verifyPeer ? '1' : '0') . ')');

        $socket = @stream_socket_client(
            $transport . ':' . $port,
            $errno,
            $errstr,
            $timeout,
            STREAM_CLIENT_CONNECT,
            $context
        );

        if (!$socket) {
            $this->error = "Could not connect to SMTP host ($errstr).";
            $this->log('FAIL: ' . $this->error);
            return false;
        }
        stream_set_timeout($socket, $timeout);

        try {
            $this->expect($socket, 220);

            $ehloHost = $_SERVER['SERVER_NAME'] ?? 'localhost';
            $this->cmd($socket, 'EHLO ' . $ehloHost, 250);

            if ($encryption === 'tls') {
                $this->cmd($socket, 'STARTTLS', 220);
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new \RuntimeException('Failed to start TLS encryption.');
                }
                $this->cmd($socket, 'EHLO ' . $ehloHost, 250);
            }

            if ($username !== '') {
                $this->cmd($socket, 'AUTH LOGIN', 334);
                $this->cmd($socket, base64_encode($username), 334);
                $this->cmd($socket, base64_encode($password), 235);
            }

            $this->cmd($socket, 'MAIL FROM:<' . $fromEmail . '>', 250);
            $this->cmd($socket, 'RCPT TO:<' . $toEmail . '>', [250, 251]);
            $this->cmd($socket, 'DATA', 354);

            $message = $this->buildMessage(
                $subject,
                $body,
                $fromEmail,
                $fromName,
                $toEmail,
                $toName,
                $replyTo,
                $replyName
            );

            fwrite($socket, $message . "\r\n.\r\n");
            $this->expect($socket, 250);

            $this->cmd($socket, 'QUIT', [221]);
            fclose($socket);
            $this->log('OK: delivered to ' . $toEmail);
            return true;
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->log('FAIL: ' . $this->error);
            @fclose($socket);
            return false;
        }
    }

    /** Append a line to storage/logs/mail.log when logging is enabled. */
    private function log(string $line): void
    {
        if (empty($this->cfg['log']) || !defined('BASE_PATH')) {
            return;
        }
        $dir = BASE_PATH . '/storage/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0700, true);
        }
        @file_put_contents(
            $dir . '/mail.log',
            '[' . date('Y-m-d H:i:s') . '] ' . $line . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }

    /** @param int|int[] $expected */
    private function cmd($socket, string $command, $expected): void
    {
        fwrite($socket, $command . "\r\n");
        $this->expect($socket, $expected);
    }

    /** @param int|int[] $expected */
    private function expect($socket, $expected): void
    {
        $expected = (array) $expected;
        $response = '';
        // Read the (possibly multi-line) SMTP reply
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (isset($line[3]) && $line[3] === ' ') {
                break;
            }
        }
        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $expected, true)) {
            throw new \RuntimeException('Unexpected SMTP reply: ' . trim($response));
        }
    }

    private function buildMessage(
        string $subject,
        string $body,
        string $fromEmail,
        string $fromName,
        string $toEmail,
        string $toName,
        ?string $replyTo,
        ?string $replyName
    ): string {
        $eol = "\r\n";
        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        $headers   = [];
        $headers[] = 'Date: ' . date('r');
        $headers[] = 'From: ' . $this->formatAddress($fromName, $fromEmail);
        $headers[] = 'To: ' . $this->formatAddress($toName, $toEmail);
        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $this->formatAddress($replyName ?? '', $replyTo);
        }
        $headers[] = 'Subject: ' . $encodedSubject;
        $headers[] = 'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . ($_SERVER['SERVER_NAME'] ?? 'hawkinsconstruction.com') . '>';
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-Type: text/plain; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';
        $headers[] = 'X-Mailer: Hawkins-MVC';

        // Normalize line endings and dot-stuff the body
        $body = str_replace(["\r\n", "\r", "\n"], $eol, $body);
        $body = preg_replace('/^\./m', '..', $body);

        return implode($eol, $headers) . $eol . $eol . $body;
    }

    private function formatAddress(string $name, string $email): string
    {
        $name = trim($name);
        if ($name === '') {
            return '<' . $email . '>';
        }
        return '=?UTF-8?B?' . base64_encode($name) . '?= <' . $email . '>';
    }
}
