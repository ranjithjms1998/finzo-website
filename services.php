<?php
$base = '';
$active = 'services';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Our Services | Finzo Financial Services</title>
<meta name="description" content="Explore Finzo Financial Services' full range of financial solutions: Personal Loan, Business Loan, Housing Loan, LAP, Car Loan, Short Term Finance and Channel Finance.">
<meta property="og:type" content="website">
<meta property="og:title" content="Our Services | Finzo Financial Services">
<meta property="og:description" content="A complete range of financial solutions for personal, business, housing and vehicle requirements.">
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<header class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Services</li>
      </ol>
    </nav>
    <h1>Our Financial Services</h1>
    <p>A complete range of financial solutions designed to support your personal, business and property requirements &mdash; guided every step of the way.</p>
  </div>
</header>

<section class="section-padding">
  <div class="container">
    <div class="row g-4">

      <div class="col-lg-4 col-md-6" data-aos="fade-up">
        <div class="service-card">
          <span class="icon-box"><i class="bi bi-wallet2"></i></span>
          <h3>Personal Loan</h3>
          <p>Flexible financial support for personal needs such as medical, travel, education or family expenses.</p>
          <div class="card-actions">
            <a href="services/personal-loan.php" class="btn btn-outline-navy">Learn More</a>
            <a href="apply-now.php" class="btn btn-gold">Enquire Now</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-delay="1">
        <div class="service-card">
          <span class="icon-box"><i class="bi bi-briefcase"></i></span>
          <h3>Unsecured Business Loan</h3>
          <p>Business financial support without collateral, structured to help you grow and manage cash flow.</p>
          <div class="card-actions">
            <a href="services/business-loan.php" class="btn btn-outline-navy">Learn More</a>
            <a href="apply-now.php" class="btn btn-gold">Enquire Now</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-delay="2">
        <div class="service-card">
          <span class="icon-box"><i class="bi bi-house-door"></i></span>
          <h3>Housing Loan</h3>
          <p>Assistance in securing financial solutions for purchasing, constructing or renovating your home.</p>
          <div class="card-actions">
            <a href="services/housing-loan.php" class="btn btn-outline-navy">Learn More</a>
            <a href="apply-now.php" class="btn btn-gold">Enquire Now</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="fade-up">
        <div class="service-card">
          <span class="icon-box"><i class="bi bi-building"></i></span>
          <h3>Loan Against Property</h3>
          <p>Unlock the value of your property with financial solutions secured against residential or commercial assets.</p>
          <div class="card-actions">
            <a href="services/loan-against-property.php" class="btn btn-outline-navy">Learn More</a>
            <a href="apply-now.php" class="btn btn-gold">Enquire Now</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-delay="1">
        <div class="service-card">
          <span class="icon-box"><i class="bi bi-car-front"></i></span>
          <h3>Car Loan &ndash; New &amp; Used</h3>
          <p>Financial assistance for purchasing a new or pre-owned car, tailored to your budget and requirement.</p>
          <div class="card-actions">
            <a href="services/car-loan.php" class="btn btn-outline-navy">Learn More</a>
            <a href="apply-now.php" class="btn btn-gold">Enquire Now</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-delay="2">
        <div class="service-card">
          <span class="icon-box"><i class="bi bi-clock-history"></i></span>
          <h3>Short Term Finance</h3>
          <p>Quick, short-duration financial support designed for urgent or temporary business and personal needs.</p>
          <div class="card-actions">
            <a href="services/short-term-finance.php" class="btn btn-outline-navy">Learn More</a>
            <a href="apply-now.php" class="btn btn-gold">Enquire Now</a>
          </div>
        </div>
      </div>

      <div class="col-lg-4 col-md-6 mx-md-auto" data-aos="fade-up">
        <div class="service-card">
          <span class="icon-box"><i class="bi bi-diagram-3"></i></span>
          <h3>Channel Finance</h3>
          <p>Structured financial solutions supporting dealers, distributors and channel partners in the supply chain.</p>
          <div class="card-actions">
            <a href="services/channel-finance.php" class="btn btn-outline-navy">Learn More</a>
            <a href="apply-now.php" class="btn btn-gold">Enquire Now</a>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<section class="section-padding-sm bg-light-section">
  <div class="container">
    <div class="cta-section" data-aos="fade-up">
      <h2>Not Sure Which Solution Fits You?</h2>
      <p>Share your requirement with our team and we'll help you identify the most suitable financial solution.</p>
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
