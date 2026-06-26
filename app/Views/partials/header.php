<?php $current = current_path(); ?>
<header class="site-header" id="top">
  <div class="container nav-inner">
    <a href="/" class="brand" aria-label="Hawkins Construction home">
      <?php include __DIR__ . '/_brand-mark.php'; ?>
      <span class="brand-text"><span class="brand-name">HAWKINS</span><span class="brand-sub">Construction &amp; Supplies</span></span>
    </a>

    <nav class="primary-nav" id="primary-nav" aria-label="Primary">
      <ul>
        <?php foreach (nav_items() as $item): $active = nav_active($item['href'], $current); ?>
        <li><a href="<?= e($item['href']) ?>" class="nav-link<?= $active ? ' is-active' : '' ?>"<?= $active ? ' aria-current="page"' : '' ?>><?= e($item['label']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="nav-actions">
      <?php include __DIR__ . '/_theme-toggle.php'; ?>
      <a href="/contact" class="btn btn-orange btn-sm nav-cta">Contact us<svg class="arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg></a>
      <button class="hamburger" id="hamburger" type="button" aria-label="Open menu" aria-controls="primary-nav" aria-expanded="false"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
<div class="nav-scrim" id="nav-scrim" hidden></div>
