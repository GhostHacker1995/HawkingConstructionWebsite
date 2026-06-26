<?php
/**
 * Site layout. Receives from View::render():
 *   $title, $description, $canonicalUrl, $ogImage, $ogType,
 *   $schema (raw JSON-LD), $headExtra (raw <link> tags),
 *   $content (rendered page HTML), $assetVersion, $baseUrl
 */
$v = '?v=' . e($assetVersion);
?><!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= e($title) ?></title>
  <meta name="description" content="<?= e($description) ?>">
  <meta name="author" content="Hawkins Construction and General Supplies Ltd">
  <meta name="theme-color" content="#ee3f23">

  <meta property="og:type" content="<?= e($ogType) ?>">
  <meta property="og:site_name" content="Hawkins Construction and General Supplies Ltd">
  <meta property="og:title" content="<?= e($title) ?>">
  <meta property="og:description" content="<?= e($description) ?>">
  <meta property="og:url" content="<?= e($canonicalUrl) ?>">
  <meta property="og:image" content="<?= e($ogImage) ?>">
  <meta property="og:locale" content="en_UG">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= e($title) ?>">
  <meta name="twitter:description" content="<?= e($description) ?>">
  <meta name="twitter:image" content="<?= e($ogImage) ?>">

  <link rel="icon" type="image/png" href="/assets/images/logos/png-logos/favicon.png">
  <link rel="canonical" href="<?= e($canonicalUrl) ?>">

  <script>
    (function () { try { var t = localStorage.getItem('hawkins-theme'); if (t === 'dark' || t === 'light') document.documentElement.setAttribute('data-theme', t); } catch (e) {} })();
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/style.css<?= $v ?>">
<?= $headExtra ?? '' ?>
<?php if (!empty($schema)): ?>
  <script type="application/ld+json"><?= $schema ?></script>
<?php endif; ?>
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <?php include APP_PATH . '/Views/partials/header.php'; ?>

  <main id="main">
<?= $content ?>
  </main>

  <?php include APP_PATH . '/Views/partials/footer.php'; ?>

  <script src="/assets/js/script.js<?= $v ?>"></script>
</body>
</html>
