/* =========================================================
   HAWKINS CONSTRUCTION - shared interactions (all pages)
   ========================================================= */
(function () {
  'use strict';

  var root = document.documentElement;
  var STORE_KEY = 'hawkins-theme';

  /* ---------- Theme toggle ---------- */
  var toggle = document.getElementById('theme-toggle');
  function currentTheme() { return root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light'; }
  function applyTheme(theme) {
    root.setAttribute('data-theme', theme);
    if (toggle) toggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
  }
  if (toggle) {
    applyTheme(currentTheme());
    toggle.addEventListener('click', function () {
      var next = currentTheme() === 'dark' ? 'light' : 'dark';
      applyTheme(next);
      try { localStorage.setItem(STORE_KEY, next); } catch (e) {}
    });
  }

  /* ---------- Nav state: transparent over hero, solid once scrolled ---------- */
  /* Pages without a hero (inner pages) stay solid from the top. */
  var header = document.querySelector('.site-header');
  if (header) {
    var hasHero = !!document.querySelector('.hero');
    var setNavState = function () {
      var solid = !hasHero || window.scrollY > 40;
      header.classList.toggle('nav-solid', solid);
      header.classList.toggle('nav-transparent', !solid);
    };
    setNavState();
    window.addEventListener('scroll', setNavState, { passive: true });
  }

  /* ---------- Mobile menu ---------- */
  var hamburger = document.getElementById('hamburger');
  var nav = document.getElementById('primary-nav');
  var scrim = document.getElementById('nav-scrim');
  function setMenu(open) {
    if (!nav || !hamburger) return;
    nav.classList.toggle('open', open);
    hamburger.setAttribute('aria-expanded', open ? 'true' : 'false');
    hamburger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
    if (scrim) {
      scrim.hidden = !open;
      requestAnimationFrame(function () { scrim.classList.toggle('show', open); });
    }
    document.body.style.overflow = open ? 'hidden' : '';
  }
  if (hamburger) hamburger.addEventListener('click', function () { setMenu(!nav.classList.contains('open')); });
  if (scrim) scrim.addEventListener('click', function () { setMenu(false); });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && nav && nav.classList.contains('open')) setMenu(false);
  });

  /* ---------- Smooth scroll for in-page anchors + close menu ---------- */
  document.querySelectorAll('a[href^="#"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      var id = link.getAttribute('href');
      if (id.length > 1) {
        var target = document.querySelector(id);
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth', block: 'start' });
          history.replaceState(null, '', id);
        }
      }
      setMenu(false);
    });
  });
  document.querySelectorAll('.primary-nav a').forEach(function (link) {
    link.addEventListener('click', function () { setMenu(false); });
  });

  /* ---------- Scroll spy (only for same-page #section nav links) ---------- */
  var navLinks = Array.prototype.slice.call(document.querySelectorAll('.nav-link'));
  var sectionMap = {};
  navLinks.forEach(function (link) {
    var href = link.getAttribute('href') || '';
    if (href.charAt(0) === '#' && href.length > 1) {
      var sec = document.getElementById(href.slice(1));
      if (sec) sectionMap[href.slice(1)] = link;
    }
  });
  var spiedSections = Object.keys(sectionMap).map(function (id) { return document.getElementById(id); });
  if ('IntersectionObserver' in window && spiedSections.length) {
    var spy = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          navLinks.forEach(function (l) { l.classList.remove('is-active'); });
          var link = sectionMap[entry.target.id];
          if (link) link.classList.add('is-active');
        }
      });
    }, { rootMargin: '-45% 0px -50% 0px', threshold: 0 });
    spiedSections.forEach(function (s) { spy.observe(s); });
  }

  /* ---------- Scroll reveal ---------- */
  var revealEls = document.querySelectorAll('[data-reveal]');
  if ('IntersectionObserver' in window) {
    var revealObs = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) { entry.target.classList.add('in'); obs.unobserve(entry.target); }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    revealEls.forEach(function (el) { revealObs.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add('in'); });
  }

  /* ---------- Project filters ---------- */
  var filterBtns = document.querySelectorAll('.filter-btn');
  var projectCards = document.querySelectorAll('.project-card[data-category]');
  filterBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      filterBtns.forEach(function (b) { b.classList.remove('is-active'); });
      btn.classList.add('is-active');
      var filter = btn.getAttribute('data-filter');
      projectCards.forEach(function (card) {
        var show = filter === 'all' || card.getAttribute('data-category') === filter;
        card.classList.toggle('is-hidden', !show);
      });
    });
  });

  /* ---------- FAQ accordion ---------- */
  document.querySelectorAll('.faq-q').forEach(function (q) {
    q.addEventListener('click', function () {
      var item = q.closest('.faq-item');
      var isOpen = item.classList.contains('open');
      item.classList.toggle('open', !isOpen);
      q.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
    });
  });

  /* ---------- Contact form (AJAX to the PHP backend, graceful fallback) ---------- */
  var form = document.getElementById('contact-form');
  var status = document.getElementById('form-status');
  if (form && status && window.fetch) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var btn = form.querySelector('[type="submit"]');
      var label = btn ? btn.textContent : '';
      if (btn) { btn.disabled = true; btn.textContent = 'Sending...'; }
      status.textContent = '';
      status.className = 'form-status';
      // Clear any prior inline field errors
      form.querySelectorAll('.field-error').forEach(function (el) { el.remove(); });

      fetch(form.action || window.location.href, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        body: new FormData(form)
      })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          status.textContent = data.message || (data.ok ? 'Message sent.' : 'Something went wrong.');
          status.className = 'form-status ' + (data.ok ? 'ok' : 'err');
          if (data.ok) {
            form.reset();
          } else if (data.errors) {
            Object.keys(data.errors).forEach(function (field) {
              if (field.charAt(0) === '_') return;
              var input = form.querySelector('[name="' + field + '"]');
              if (!input) return;
              input.setAttribute('aria-invalid', 'true');
              var span = document.createElement('span');
              span.className = 'field-error';
              span.textContent = data.errors[field];
              (input.closest('.field') || input.parentNode).appendChild(span);
            });
          }
        })
        .catch(function () {
          status.textContent = 'Network error. Please try again or email info@hawkinsconstruction.com.';
          status.className = 'form-status err';
        })
        .finally(function () {
          if (btn) { btn.disabled = false; btn.textContent = label; }
        });
    });
  }

  /* ---------- Back to top ---------- */
  var backBtn = document.getElementById('back-to-top');
  if (backBtn) {
    var onScroll = function () { backBtn.classList.toggle('show', window.scrollY > 700); };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    /* #top is the fixed header, so scrollIntoView is a no-op; scroll the window instead */
    backBtn.addEventListener('click', function (e) {
      e.preventDefault();
      var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
    });
  }

  /* ---------- Footer year ---------- */
  document.querySelectorAll('.js-year').forEach(function (el) { el.textContent = new Date().getFullYear(); });
})();
