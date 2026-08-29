<?php
$base = '';
$active = 'emi';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EMI Calculator | Finzo Financial Services</title>
<meta name="description" content="Use Finzo Financial Services' EMI calculator to estimate your monthly instalment, total interest and total payable amount instantly.">
<meta property="og:type" content="website">
<meta property="og:title" content="EMI Calculator | Finzo Financial Services">
<meta property="og:description" content="Estimate your monthly EMI instantly with our interactive calculator.">
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<header class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">EMI Calculator</li>
      </ol>
    </nav>
    <h1>EMI Calculator</h1>
    <p>Plan ahead with confidence. Estimate your monthly instalment, total interest and total payable amount instantly.</p>
  </div>
</header>

<section class="section-padding">
  <div class="container">
    <div class="emi-card" data-aos="fade-up">
      <form id="emiCalculatorForm">
        <div class="row g-0">
          <div class="col-lg-7">
            <div class="emi-inputs">
              <div class="emi-form-group">
                <label for="emiAmount">Loan Amount <span class="emi-value" id="emiAmountLabel">₹5,00,000</span></label>
                <input type="number" id="emiAmount" class="form-control" value="500000" min="10000" max="10000000" step="1000">
                <input type="range" id="emiAmountRange" class="form-range mt-2" value="500000" min="10000" max="10000000" step="1000">
              </div>
              <div class="emi-form-group">
                <label for="emiRate">Interest Rate (% p.a.) <span class="emi-value" id="emiRateLabel">10.5%</span></label>
                <input type="number" id="emiRate" class="form-control" value="10.5" min="1" max="36" step="0.1">
                <input type="range" id="emiRateRange" class="form-range mt-2" value="10.5" min="1" max="36" step="0.1">
              </div>
              <div class="emi-form-group mb-0">
                <label for="emiTenure">Loan Tenure (Years) <span class="emi-value" id="emiTenureLabel">5 yrs</span></label>
                <input type="number" id="emiTenure" class="form-control" value="5" min="1" max="30" step="1">
                <input type="range" id="emiTenureRange" class="form-range mt-2" value="5" min="1" max="30" step="1">
              </div>
            </div>
          </div>
          <div class="col-lg-5">
            <div class="emi-results">
              <h5 class="text-white mb-3">Your Estimated Repayment</h5>
              <div class="emi-donut-wrap">
                <div class="emi-donut" id="emiDonut"><span>Interest vs<br>Principal</span></div>
              </div>
              <div class="emi-result-item emi-highlight">
                <span>Monthly EMI</span>
                <span class="result-value" id="emiMonthlyResult">₹0</span>
              </div>
              <div class="emi-result-item">
                <span>Total Interest Payable</span>
                <span class="result-value" id="emiInterestResult">₹0</span>
              </div>
              <div class="emi-result-item">
                <span>Total Amount Payable</span>
                <span class="result-value" id="emiTotalResult">₹0</span>
              </div>
              <a href="apply-now.php" class="btn btn-gold w-100 mt-4">Apply for This Amount</a>
            </div>
          </div>
        </div>
      </form>
    </div>
    <p class="text-center text-muted mt-3" style="font-size:0.85rem;">*This calculator provides an indicative estimate only, using the standard reducing-balance EMI formula. Actual EMI may vary based on lender terms and eligibility.</p>
  </div>
</section>

<section class="section-padding-sm bg-light-section">
  <div class="container">
    <div class="section-header-center" data-aos="fade-up">
      <span class="section-eyebrow">Helpful To Know</span>
      <h2 class="section-title">Understanding Your <span>EMI</span></h2>
    </div>
    <div class="row g-4">
      <div class="col-md-4" data-aos="fade-up">
        <div class="feature-block">
          <span class="icon-box"><i class="bi bi-cash-coin"></i></span>
          <div><h4>Loan Amount</h4><p>The total principal amount you intend to borrow.</p></div>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-delay="1">
        <div class="feature-block">
          <span class="icon-box"><i class="bi bi-percent"></i></span>
          <div><h4>Interest Rate</h4><p>The annual interest rate applicable, as offered by the lender.</p></div>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-delay="2">
        <div class="feature-block">
          <span class="icon-box"><i class="bi bi-calendar3"></i></span>
          <div><h4>Loan Tenure</h4><p>The repayment period, in years, over which you repay the loan.</p></div>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section-padding-sm">
  <div class="container">
    <div class="cta-section" data-aos="fade-up">
      <h2>Ready to Take the Next Step?</h2>
      <p>Submit your enquiry and our team will help you explore suitable financial solutions.</p>
      <div class="cta-actions">
        <a href="apply-now.php" class="btn btn-gold">Apply Now</a>
        <a href="contact.php" class="btn btn-outline-light">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php $includeCalculator = true; include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
