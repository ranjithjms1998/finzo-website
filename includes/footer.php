<?php
/**
 * Shared site footer + back-to-top button.
 * Expects $base ('' for root pages, '../' for one-level-deep pages) to be
 * set by the including page before this file is included.
 */
if (!isset($base)) { $base = ''; }
?>
<footer class="finzo-footer">
  <div class="container">
    <div class="row gy-5">
      <div class="col-lg-4">
        <a class="finzo-logo mb-3 d-inline-flex" href="<?php echo $base; ?>index.php">
          <span class="finzo-logo__emblem"><span>S</span></span>
          <span class="finzo-logo__text"><strong>FINZO</strong><small>Financial Services</small></span>
        </a>
        <p>Finzo Financial Services provides financial and loan assistance for personal, business, housing, property and vehicle requirements, guided by transparency and professionalism.</p>
        <div class="footer-social">
          <a href="tel:+919944270207" aria-label="Call Finzo Financial Services"><i class="bi bi-telephone"></i></a>
          <a href="mailto:finzofinancialservices26@gmail.com" aria-label="Email Finzo Financial Services"><i class="bi bi-envelope"></i></a>
          <a href="<?php echo $base; ?>contact.php" aria-label="Locate Finzo Financial Services"><i class="bi bi-geo-alt"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h5>Quick Links</h5>
        <ul>
          <li><a href="<?php echo $base; ?>index.php">Home</a></li>
          <li><a href="<?php echo $base; ?>about.php">About Us</a></li>
          <li><a href="<?php echo $base; ?>services.php">Services</a></li>
          <li><a href="<?php echo $base; ?>emi-calculator.php">EMI Calculator</a></li>
          <li><a href="<?php echo $base; ?>faq.php">FAQ</a></li>
          <li><a href="<?php echo $base; ?>contact.php">Contact</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-3">
        <h5>Services</h5>
        <ul>
          <li><a href="<?php echo $base; ?>services/personal-loan.php">Personal Loan</a></li>
          <li><a href="<?php echo $base; ?>services/business-loan.php">Business Loan</a></li>
          <li><a href="<?php echo $base; ?>services/housing-loan.php">Housing Loan</a></li>
          <li><a href="<?php echo $base; ?>services/loan-against-property.php">LAP</a></li>
          <li><a href="<?php echo $base; ?>services/car-loan.php">Car Loan</a></li>
          <li><a href="<?php echo $base; ?>services/short-term-finance.php">Short Term Finance</a></li>
          <li><a href="<?php echo $base; ?>services/channel-finance.php">Channel Finance</a></li>
        </ul>
      </div>
      <div class="col-lg-3">
        <h5>Contact</h5>
        <div class="footer-contact-item"><i class="bi bi-telephone"></i><span>+91 9944 270 207</span></div>
        <div class="footer-contact-item"><i class="bi bi-envelope"></i><span>finzofinancialservices26@gmail.com</span></div>
        <div class="footer-contact-item"><i class="bi bi-geo-alt"></i><span>No. 1/1282/A, Vasanveli 7th Cross, Nachikurichi, Trichy &ndash; 620102</span></div>
      </div>
    </div>

    <div class="footer-disclaimer">
      <strong class="text-gold">Disclaimer:</strong> Finzo Financial Services facilitates access to financial solutions and loan-related assistance. Loan approval, interest rates, tenure, fees and other terms are subject to the policies, eligibility criteria and approval processes of the respective financial institution/lender. Submission of an enquiry does not guarantee loan approval or disbursal.
    </div>

    <div class="footer-bottom d-flex flex-wrap justify-content-between align-items-center gap-3">
      <span>&copy; <span data-year></span> Finzo Financial Services. All Rights Reserved.</span>
      <div class="d-flex gap-3 flex-wrap align-items-center">
        <a href="<?php echo $base; ?>legal/privacy-policy.php">Privacy Policy</a>
        <a href="<?php echo $base; ?>legal/terms.php">Terms &amp; Conditions</a>
        <a href="<?php echo $base; ?>legal/disclaimer.php">Disclaimer</a>
        <a href="<?php echo $base; ?>admin/public/login" class="d-inline-flex align-items-center gap-1" style="opacity:0.6;" title="Admin Login"><i class="bi bi-shield-lock"></i> Admin Login</a>
      </div>
    </div>
  </div>
</footer>

<button class="back-to-top" aria-label="Back to top"><i class="bi bi-arrow-up"></i></button>
