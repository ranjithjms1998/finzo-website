<?php
$base = '';
$active = 'faq';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ | Finzo Financial Services</title>
<meta name="description" content="Frequently asked questions about Finzo Financial Services' loan and financial assistance for personal, business, housing, property and vehicle needs.">
<meta property="og:type" content="website">
<meta property="og:title" content="FAQ | Finzo Financial Services">
<meta property="og:description" content="Quick answers to common questions about our financial services.">
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<header class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">FAQ</li>
      </ol>
    </nav>
    <h1>Frequently Asked Questions</h1>
    <p>Quick answers to common questions about our financial services. Can't find what you're looking for? Reach out to us directly.</p>
  </div>
</header>

<section class="section-padding">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9" data-aos="fade-up">
        <div class="accordion finzo-accordion" id="faqAccordion">
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">What types of loans do you assist with?</button>
            </h3>
            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
              <div class="accordion-body">We assist with Personal Loans, Unsecured Business Loans, Housing Loans, Loan Against Property, Car Loans (new &amp; used), Short Term Finance and Channel Finance.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">What is a personal loan?</button>
            </h3>
            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">A personal loan is financial assistance that can be used for various personal needs such as medical expenses, travel, education or family requirements, based on the lender's eligibility criteria.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">Can I enquire about a business loan?</button>
            </h3>
            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Yes. You can submit an enquiry for our Unsecured Business Loan assistance through our enquiry form, and our team will get in touch with you to understand your requirement.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">Do you assist with housing loans?</button>
            </h3>
            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Yes, we provide assistance for housing loans related to purchase, construction and renovation requirements. Reach out to us with your requirement to get started.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">What is a Loan Against Property?</button>
            </h3>
            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">A Loan Against Property (LAP) allows you to use your residential or commercial property as security to access financial solutions for personal or business needs.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">Can I enquire about a new or used car loan?</button>
            </h3>
            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Yes, we assist with financial solutions for both new and used car purchases. Submit your enquiry and our team will guide you through the available options.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7">What documents may be required?</button>
            </h3>
            <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">Document requirements vary by loan type and lender, and generally include identity proof, address proof, income proof and relevant property or business documents. Our team will guide you based on your specific requirement.</div>
            </div>
          </div>
          <div class="accordion-item">
            <h3 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8">How can I contact Finzo Financial Services?</button>
            </h3>
            <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body">You can call us at +91 9944 270 207, email finzofinancialservices26@gmail.com, or visit us at No. 1/1282/A, Vasanveli 7th Cross, Nachikurichi, Trichy - 620102. You may also use our online enquiry form.</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-padding-sm bg-light-section">
  <div class="container">
    <div class="cta-section" data-aos="fade-up">
      <h2>Still Have Questions?</h2>
      <p>Our team is ready to help. Get in touch and we'll respond to your enquiry promptly.</p>
      <div class="cta-actions">
        <a href="apply-now.php" class="btn btn-gold">Apply Now</a>
        <a href="contact.php" class="btn btn-outline-light">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php $includeCalculator = false; include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
