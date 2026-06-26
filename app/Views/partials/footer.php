<?php $co = company(); ?>
<footer class="site-footer">
  <div class="container footer-inner">
    <div class="footer-brand">
      <a href="/" class="brand">
        <?php include __DIR__ . '/_brand-mark.php'; ?>
        <span class="brand-text"><span class="brand-name">HAWKINS</span><span class="brand-sub">Construction &amp; Supplies</span></span>
      </a>
      <p>Building excellence. Delivering confidence. A Uganda based building and civil works contractor and supplier.</p>
    </div>

    <nav class="footer-col" aria-label="Company">
      <h4>Company</h4>
      <ul>
        <li><a href="/about">About</a></li>
        <li><a href="/services">Services</a></li>
        <li><a href="/projects">Projects</a></li>
        <li><a href="/leadership">Leadership</a></li>
      </ul>
    </nav>

    <nav class="footer-col" aria-label="Explore">
      <h4>Explore</h4>
      <ul>
        <li><a href="/hse">HSE</a></li>
        <li><a href="/contact">Contact</a></li>
        <li><a href="https://wa.me/<?= e($co['whatsapp']) ?>" target="_blank" rel="noopener">WhatsApp</a></li>
      </ul>
    </nav>

    <div class="footer-contact">
      <a class="footer-contact-row" href="mailto:<?= e($co['email']) ?>"><span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect class="ic-line" x="3.5" y="5.5" width="17" height="13" rx="2"/><path class="ic-line" d="M4 7l8 6 8-6"/></svg></span><span><?= e($co['email']) ?></span></a>
      <a class="footer-contact-row" href="tel:<?= e(str_replace(' ', '', $co['phones'][1])) ?>"><span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path class="ic-line" d="M5 4h3.5l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5V20a1.5 1.5 0 0 1-1.6 1.5A15.5 15.5 0 0 1 3.5 6.6 1.5 1.5 0 0 1 5 4z"/></svg></span><span><?= e($co['phones'][1]) ?></span></a>
      <div class="footer-contact-row"><span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path class="ic-line" d="M12 21c5-3 7.5-6.7 7.5-10.5A7.5 7.5 0 0 0 4.5 10.5C4.5 14.3 7 18 12 21z"/><circle class="ic-line" cx="12" cy="10.3" r="2.6"/></svg></span><span>Wampewo, Kasangati TC, Wakiso, Uganda</span></div>
    </div>
  </div>
  <div class="container footer-bottom">
    <p>&copy; <?= date('Y') ?> Hawkins Construction and General Supplies Ltd. All rights reserved.</p>
    <p>Website by <a href="https://grayhost.dev" target="_blank" rel="noopener">Grayhost Innovations</a></p>
  </div>
</footer>

<a class="wa-float" href="https://wa.me/<?= e($co['whatsapp']) ?>" target="_blank" rel="noopener" aria-label="Chat with us on WhatsApp"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.1-.3.2-.5.1-.7-.3-1.4-.7-2-1.4-.4-.5-.8-1-.9-1.2-.1-.2 0-.4.1-.5l.4-.5c.1-.2.1-.3.2-.5 0-.1 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 1.9 0 1.1.8 2.2 1 2.4.1.2 1.6 2.5 4 3.5.5.2.9.4 1.3.5.5.2 1 .1 1.3.1.4-.1 1.2-.5 1.4-1 .2-.5.2-.9.1-1l-.4-.3z"/></svg></a>
<a class="back-to-top" id="back-to-top" href="#top" aria-label="Back to top"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5l7 7-1.4 1.4L13 8.8V19h-2V8.8l-4.6 4.6L5 12z"/></svg></a>
