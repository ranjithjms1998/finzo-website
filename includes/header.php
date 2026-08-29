<?php
/**
 * Shared site navbar.
 * Expects (set by the including page before this file is included):
 *   $base   - '' for root-level pages, '../' for pages one folder deep
 *             (services/, legal/). Used to prefix every link back to root.
 *   $active - one of: home, about, services, emi, faq, contact
 *             (omit/empty for pages with no matching nav item, e.g. legal
 *             pages or apply-now.php).
 */
if (!isset($base)) { $base = ''; }
if (!isset($active)) { $active = ''; }

function finzo_nav_class($key, $active) {
    return $key === $active ? ' active' : '';
}
?>
<nav class="navbar navbar-expand-lg finzo-navbar" aria-label="Main navigation">
  <div class="container">
    <a class="navbar-brand finzo-logo" href="<?php echo $base; ?>index.php">
      <span class="finzo-logo__emblem"><span>S</span></span>
      <span class="finzo-logo__text">
        <strong>FINZO</strong>
        <small>Financial Services</small>
      </span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#finzoNav" aria-controls="finzoNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="finzoNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link<?php echo finzo_nav_class('home', $active); ?>" href="<?php echo $base; ?>index.php">Home</a></li>
        <li class="nav-item"><a class="nav-link<?php echo finzo_nav_class('about', $active); ?>" href="<?php echo $base; ?>about.php">About Us</a></li>
        <li class="nav-item"><a class="nav-link<?php echo finzo_nav_class('services', $active); ?>" href="<?php echo $base; ?>services.php">Services</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo $base; ?>index.php#why-choose-us">Why Choose Us</a></li>
        <li class="nav-item"><a class="nav-link<?php echo finzo_nav_class('emi', $active); ?>" href="<?php echo $base; ?>emi-calculator.php">EMI Calculator</a></li>
        <li class="nav-item"><a class="nav-link<?php echo finzo_nav_class('faq', $active); ?>" href="<?php echo $base; ?>faq.php">FAQ</a></li>
        <li class="nav-item"><a class="nav-link<?php echo finzo_nav_class('contact', $active); ?>" href="<?php echo $base; ?>contact.php">Contact</a></li>
      </ul>
      <a href="<?php echo $base; ?>apply-now.php" class="btn btn-gold btn-sm-pill<?php echo $active === 'apply' ? ' active' : ''; ?>">Apply Now</a>
    </div>
  </div>
</nav>
