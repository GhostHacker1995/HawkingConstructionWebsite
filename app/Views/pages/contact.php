<?php
$title = 'Contact Hawkins Construction | Kasangati, Wakiso, Uganda';
$description = 'Contact Hawkins Construction and General Supplies Ltd in Wampewo, Kasangati, Wakiso District, Uganda. Phone, email, contact form and WhatsApp.';
$canonical = '/contact';

$status = $flashStatus ?? null;
$errors = $flashErrors ?? [];
$old    = $old ?? [];
$services = ['General enquiry', 'Design and Build', 'General Construction', 'Project Management', 'Property Development', 'Materials Supply'];
?>
<section class="page-header">
  <div class="container">
    <nav class="breadcrumb" aria-label="Breadcrumb"><a href="/">Home</a><span class="sep">/</span><span aria-current="page">Contact</span></nav>
    <h1 class="page-title">Let's build your project together.</h1>
    <p class="page-header-sub">Share a few details and our team will respond with next steps. For a quick chat, reach us on WhatsApp.</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="contact-layout">
      <div class="contact-details reveal" data-reveal>
        <span class="contact-chip"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 21V8l5-3 5 3v13M14 21V11l5-3 1 .6V21M3 21h18M7 9.5h0M7 12.5h0M7 15.5h0"/></svg>Contact</span>
        <h2 class="contact-heading">Get in touch here.</h2>
        <p class="contact-intro">Whether you have a question about our services, pricing or need any other details, reach us using the form or the information below.</p>
        <ul class="contact-list">
          <li>
            <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path class="ic-line" d="M5 4h3.5l1.5 4-2 1.5a11 11 0 0 0 5 5l1.5-2 4 1.5V20a1.5 1.5 0 0 1-1.6 1.5A15.5 15.5 0 0 1 3.5 6.6 1.5 1.5 0 0 1 5 4z"/></svg></span>
            <div class="contact-vals">
              <a href="tel:+256775718929">+256 775 718 929</a>
              <a href="tel:+256704823099">+256 704 823 099</a>
              <a href="tel:+256788137549">+256 788 137 549</a>
              <a href="tel:+256752036122">+256 752 036 122</a>
            </div>
          </li>
          <li>
            <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect class="ic-line" x="3.5" y="5.5" width="17" height="13" rx="2"/><path class="ic-line" d="M4 7l8 6 8-6"/></svg></span>
            <div class="contact-vals"><a href="mailto:info@hawkinsconstruction.com">info@hawkinsconstruction.com</a></div>
          </li>
          <li>
            <span class="contact-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path class="ic-line" d="M12 21c5-3 7.5-6.7 7.5-10.5A7.5 7.5 0 0 0 4.5 10.5C4.5 14.3 7 18 12 21z"/><circle class="ic-line" cx="12" cy="10.3" r="2.6"/></svg></span>
            <div class="contact-vals"><span>Wampewo, Kasangati Town Council, Wakiso District, Uganda</span></div>
          </li>
        </ul>
        <a class="btn btn-whatsapp" href="https://wa.me/256704823099?text=Hello%20Hawkins%20Construction%2C%20I%20would%20like%20to%20discuss%20a%20project." target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" aria-hidden="true" class="wa"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm0 18.2a8.2 8.2 0 0 1-4.2-1.2l-.3-.2-3 .8.8-2.9-.2-.3A8.2 8.2 0 1 1 12 20.2zm4.5-6.1c-.2-.1-1.5-.7-1.7-.8-.2-.1-.4-.1-.6.1-.2.2-.6.8-.8 1-.1.1-.3.2-.5.1-.7-.3-1.4-.7-2-1.4-.4-.5-.8-1-.9-1.2-.1-.2 0-.4.1-.5l.4-.5c.1-.2.1-.3.2-.5 0-.1 0-.3 0-.4l-.8-1.8c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.4.1-.6.3-.2.2-.8.8-.8 1.9 0 1.1.8 2.2 1 2.4.1.2 1.6 2.5 4 3.5.5.2.9.4 1.3.5.5.2 1 .1 1.3.1.4-.1 1.2-.5 1.4-1 .2-.5.2-.9.1-1l-.4-.3z"/></svg>
          Chat on WhatsApp
        </a>
      </div>

      <div class="contact-form-wrap reveal" data-reveal>
        <form class="contact-form" id="contact-form" method="post" action="/contact" novalidate>
          <?= \App\Core\Csrf::field() ?>
          <input type="hidden" name="source" value="Contact form">
          <div class="hp-field" aria-hidden="true">
            <label>Leave this field empty<input type="text" name="company_website" tabindex="-1" autocomplete="off"></label>
          </div>

          <div class="field-row">
            <div class="field">
              <label for="cf-name">Name</label>
              <input type="text" id="cf-name" name="name" autocomplete="name" placeholder="John Wick" value="<?= e($old['name'] ?? '') ?>" required>
              <?php if (!empty($errors['name'])): ?><span class="field-error"><?= e($errors['name']) ?></span><?php endif; ?>
            </div>
            <div class="field">
              <label for="cf-email">Email</label>
              <input type="email" id="cf-email" name="email" autocomplete="email" placeholder="you@example.com" value="<?= e($old['email'] ?? '') ?>" required>
              <?php if (!empty($errors['email'])): ?><span class="field-error"><?= e($errors['email']) ?></span><?php endif; ?>
            </div>
          </div>
          <div class="field-row">
            <div class="field">
              <label for="cf-phone">Phone</label>
              <input type="tel" id="cf-phone" name="phone" autocomplete="tel" placeholder="+256 700 000 000" value="<?= e($old['phone'] ?? '') ?>">
              <?php if (!empty($errors['phone'])): ?><span class="field-error"><?= e($errors['phone']) ?></span><?php endif; ?>
            </div>
            <div class="field">
              <label for="cf-subject">Service</label>
              <select id="cf-subject" name="subject">
                <?php foreach ($services as $s): ?>
                <option value="<?= e($s) ?>"<?= (($old['service'] ?? '') === $s) ? ' selected' : '' ?>><?= e($s) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
          <div class="field">
            <label for="cf-message">Message</label>
            <textarea id="cf-message" name="message" rows="5" placeholder="Tell us about your project" required><?= e($old['message'] ?? '') ?></textarea>
            <?php if (!empty($errors['message'])): ?><span class="field-error"><?= e($errors['message']) ?></span><?php endif; ?>
          </div>
          <button type="submit" class="btn-submit">Submit</button>
          <p class="form-status<?= $status ? ' ' . ($status['type'] === 'ok' ? 'ok' : 'err') : '' ?>" id="form-status" role="status" aria-live="polite"><?= $status ? e($status['text']) : '' ?></p>
        </form>
      </div>
    </div>

    <div class="map-wrap reveal" data-reveal>
      <iframe title="Map showing Hawkins Construction location in Kasangati, Wakiso, Uganda" src="https://www.google.com/maps?q=Kasangati%20Town%20Council%2C%20Wakiso%2C%20Uganda&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </div>
</section>

<section class="section section-soft">
  <div class="container">
    <div class="section-head center">
      <p class="eyebrow reveal" data-reveal>FAQ</p>
      <h2 class="section-title reveal" data-reveal>Customers frequently ask.</h2>
    </div>
    <div class="faq-grid reveal" data-reveal>
      <div class="faq-item">
        <button class="faq-q" type="button" aria-expanded="false">What services do you offer?<svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></button>
        <div class="faq-a"><p>Design and build, general construction, project management, property development and construction materials supply across Uganda.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q" type="button" aria-expanded="false">How do I start a project?<svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></button>
        <div class="faq-a"><p>Send us a message or reach out on WhatsApp with your scope. We will arrange a consultation and prepare a proposal.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q" type="button" aria-expanded="false">Do you provide cost estimates?<svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></button>
        <div class="faq-a"><p>Yes. After understanding your requirements we provide a clear, itemised quotation with no hidden costs.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q" type="button" aria-expanded="false">Where do you operate?<svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></button>
        <div class="faq-a"><p>We are based in Wakiso District and take on projects across Uganda, from residential builds to commercial infrastructure.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q" type="button" aria-expanded="false">Can you work with my budget?<svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></button>
        <div class="faq-a"><p>We offer cost effective solutions and will advise on the best approach to deliver quality within your budget.</p></div>
      </div>
      <div class="faq-item">
        <button class="faq-q" type="button" aria-expanded="false">What makes you different?<svg class="chev" viewBox="0 0 24 24" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg></button>
        <div class="faq-a"><p>An experienced leadership team, proven expertise, timely delivery, a reliable supply network and a safety first culture.</p></div>
      </div>
    </div>
  </div>
</section>

<section class="cta-final">
  <div class="container">
    <h2 class="reveal" data-reveal>Let's build something exceptional together.</h2>
    <p class="reveal" data-reveal>Prefer to talk first? Message us on WhatsApp and we will get straight back to you.</p>
    <div class="cta-final-actions reveal" data-reveal>
      <a href="https://wa.me/256704823099" target="_blank" rel="noopener" class="btn btn-orange">Request a quote<svg class="arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg></a>
      <a href="tel:+256704823099" class="btn btn-outline">Call us now<svg class="arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M9 6l6 6-6 6"/></svg></a>
    </div>
  </div>
</section>
