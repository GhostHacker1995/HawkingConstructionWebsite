<?php
/**
 * ============================================================
 *  SMTP MAIL CONFIGURATION
 * ============================================================
 *  The contact form is delivered to "to_email".
 *
 *  cPanel mailbox settings:
 *    host        : mail.yourdomain.com  (or the server hostname)
 *    port        : 465 (ssl)  or  587 (tls)
 *    encryption  : 'ssl'      or  'tls'
 *    username    : the full mailbox address
 *    password    : that mailbox's password
 *
 *  If sending fails with a certificate / connection error, set
 *  'verify_peer' to false (shared mail servers often present a
 *  certificate for a different hostname). Errors are written to
 *  storage/logs/mail.log when 'log' is true.
 *
 *  ---- LOCAL TESTING ----
 *  XAMPP cannot send real SMTP mail by default. Leave the real
 *  credentials here for production; locally the form still works
 *  end to end and the attempt (and any error) is logged to
 *  storage/logs/mail.log.
 * ============================================================
 */
return [
    // --- SMTP server ---
    'host'       => 'mail.hawkinsconstruction.com',
    'port'       => 465,
    'encryption' => 'ssl',            // 'ssl' | 'tls' | 'none'
    'timeout'    => 20,

    // --- Authentication ---
    'username'   => 'info@hawkinsconstruction.com',
    'password'   => 'CHANGE_ME',

    // --- Sender shown on the email ---
    'from_email' => 'info@hawkinsconstruction.com',
    'from_name'  => 'Hawkins Construction Website',

    // --- Where every submission is delivered ---
    'to_email'   => 'info@hawkinsconstruction.com',
    'to_name'    => 'Hawkins Construction',

    // --- TLS certificate verification ---
    // Shared-host mail servers frequently use a certificate that does not
    // match the mail hostname, which blocks the connection. Leave false
    // unless your host provides a properly matched certificate.
    'verify_peer' => false,

    // --- Logging ---
    // Write delivery attempts and SMTP errors to storage/logs/mail.log.
    'log' => true,
];
