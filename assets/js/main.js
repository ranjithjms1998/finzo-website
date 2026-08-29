/* ==========================================================================
   FINZO FINANCIAL SERVICES — Main JS
   Navbar scroll effect, mobile interactions, smooth scrolling, active link,
   back-to-top button.
   ========================================================================== */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    initNavbarScroll();
    initSmoothScroll();
    initActiveNavLink();
    initMobileMenuAutoClose();
    initBackToTop();
    initYearStamp();
  });

  /** Toggle solid navbar background once the page scrolls past the hero. */
  function initNavbarScroll() {
    var navbar = document.querySelector('.finzo-navbar');
    if (!navbar) return;

    var threshold = 60;

    function onScroll() {
      if (window.scrollY > threshold) {
        navbar.classList.add('navbar-scrolled');
      } else {
        navbar.classList.remove('navbar-scrolled');
      }
    }

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /** Smooth-scroll for in-page anchor links (e.g. "#services"). */
  function initSmoothScroll() {
    var links = document.querySelectorAll('a[href^="#"]:not([href="#"])');
    links.forEach(function (link) {
      link.addEventListener('click', function (e) {
        var targetId = link.getAttribute('href');
        var target = document.querySelector(targetId);
        if (!target) return;
        e.preventDefault();

        var navbar = document.querySelector('.finzo-navbar');
        var offset = navbar ? navbar.offsetHeight + 12 : 0;
        var top = target.getBoundingClientRect().top + window.pageYOffset - offset;

        window.scrollTo({ top: top, behavior: 'smooth' });

        var collapse = document.querySelector('.navbar-collapse.show');
        if (collapse && window.bootstrap) {
          var bsCollapse = window.bootstrap.Collapse.getOrCreateInstance(collapse);
          bsCollapse.hide();
        }
      });
    });
  }

  /** Highlight the nav link matching the current page/section. */
  function initActiveNavLink() {
    var currentPage = (window.location.pathname.split('/').pop() || 'index.html');
    var navLinks = document.querySelectorAll('.finzo-navbar .nav-link');

    navLinks.forEach(function (link) {
      var href = (link.getAttribute('href') || '').split('/').pop();
      if (href === currentPage || (currentPage === '' && href === 'index.html')) {
        link.classList.add('active');
      }
    });
  }

  /** Auto-close the mobile menu after a nav link is tapped. */
  function initMobileMenuAutoClose() {
    var collapseEl = document.querySelector('.finzo-navbar .navbar-collapse');
    if (!collapseEl || !window.bootstrap) return;

    var navLinks = collapseEl.querySelectorAll('.nav-link, .btn');
    navLinks.forEach(function (link) {
      link.addEventListener('click', function () {
        if (collapseEl.classList.contains('show')) {
          var bsCollapse = window.bootstrap.Collapse.getOrCreateInstance(collapseEl);
          bsCollapse.hide();
        }
      });
    });
  }

  /** Show/hide a back-to-top button once the user scrolls down. */
  function initBackToTop() {
    var btn = document.querySelector('.back-to-top');
    if (!btn) return;

    function onScroll() {
      if (window.scrollY > 500) {
        btn.classList.add('show');
      } else {
        btn.classList.remove('show');
      }
    }

    btn.addEventListener('click', function () {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /** Stamp the current year into any [data-year] element (footer copyright). */
  function initYearStamp() {
    var els = document.querySelectorAll('[data-year]');
    var year = new Date().getFullYear();
    els.forEach(function (el) { el.textContent = year; });
  }
})();
