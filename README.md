# Hawkins Construction and General Supplies Ltd — Website

A base-path-aware **PHP MVC** application (no framework, no Composer). It runs
unchanged at a domain root (cPanel `public_html`) or inside a subfolder
(XAMPP `htdocs/HawkinsConstructionCompany`). The contact form is delivered by a
dependency-free SMTP mailer.

## Structure

```
HawkinsConstructionCompany/
├── .htaccess              # Rewrites every request into /public
├── server.php             # Router for PHP's built-in server (local dev only)
├── app/
│   ├── .htaccess          # Denies direct web access to the app layer
│   ├── Config/            # app.php (env), routes.php, mail.php (SMTP)
│   ├── Controllers/       # Home, Page, Contact
│   ├── Core/              # Router, View, Controller, Request, Session, Csrf,
│   │                      #   Validator, Mailer, helpers
│   ├── Models/            # ContactMessage
│   └── Views/             # layouts/, partials/, pages/, errors/
├── public/                # Document root
│   ├── index.php          # Front controller
│   ├── .htaccess          # Static files served directly; else -> index.php
│   └── assets/            # css, js, images (moved here from the old /assets)
└── storage/
    ├── logs/              # mail.log
    └── sessions/          # file-based sessions (writable on shared hosting)
```

## Run locally (XAMPP)

Either of these works:

1. **Built-in PHP server** (clean URLs at the root):
   ```
   php -S 127.0.0.1:8821 -t public server.php
   ```
   Then open <http://127.0.0.1:8821/>.

2. **Apache (htdocs)**: open <http://localhost/HawkinsConstructionCompany/>.
   The root `.htaccess` forwards into `public/` and the base path
   (`/HawkinsConstructionCompany`) is auto-detected — every link still works.

## Routes

| URL          | Controller                     |
|--------------|--------------------------------|
| `/`          | HomeController@index           |
| `/about`     | PageController@about           |
| `/services`  | PageController@services        |
| `/projects`  | PageController@projects        |
| `/hse`       | PageController@hse             |
| `/leadership`| PageController@leadership      |
| `/contact`   | ContactController@index/submit |

## Contact email (SMTP)

Edit **`app/Config/mail.php`** with the cPanel mailbox details:

- `host` = `mail.yourdomain.com`, `port` = `465` (ssl) or `587` (tls)
- `username` / `password` = the full mailbox + its password
- `to_email` = where submissions are delivered

Delivery attempts and any SMTP errors are written to `storage/logs/mail.log`
(`'log' => true`). XAMPP cannot send real SMTP mail, so locally the form
validates and logs but will not deliver — that is expected.

## Deploy to cPanel

1. Upload the project so the **repository root** maps to the account's
   `public_html` (or a subdomain's document root). The root `.htaccess`
   rewrites into `public/`, so the app layer stays private.
   - Alternatively, point the domain's document root directly at `public/`.
2. Set the real SMTP credentials in `app/Config/mail.php`.
3. Ensure `storage/` is writable (755/775). Sessions and the mail log live there.
4. Bump `asset_version` in `app/Config/app.php` after any CSS/JS change to
   bust browser caches.

No `composer install`, no build step.

## CMS / admin (later)

The architecture is ready for a database-backed admin dashboard:
- Add a `Database` core class + DB-backed Models (e.g. `Project`, `Service`,
  `TeamMember`) that replace the static markup the views currently render.
- Add `AdminController`s and an `/admin` route group with an auth middleware.
- Page content currently lives in the views; the contact details are already
  centralized in `company()` (app/Core/helpers.php) as the first CMS seam.

---
Website by [Grayhost Innovations](https://grayhost.dev).
