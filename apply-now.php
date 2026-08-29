<?php
$base = '';
$active = 'apply';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Apply Now | Finzo Financial Services</title>
<meta name="description" content="Submit your enquiry to Finzo Financial Services for personal, business, housing, property or vehicle financial assistance.">
<meta property="og:type" content="website">
<meta property="og:title" content="Apply Now | Finzo Financial Services">
<meta property="og:description" content="Share your financial requirement with our team and get started.">
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<header class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Apply Now</li>
      </ol>
    </nav>
    <h1>Apply Now</h1>
    <p>Share your financial requirement with us and our team will get in touch to guide you toward a suitable solution.</p>
  </div>
</header>

<section class="section-padding">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-9" data-aos="fade-up">
        <div class="finzo-form-card">
          <div class="text-center mb-4">
            <span class="section-eyebrow">Enquiry Form</span>
            <h2 class="section-title">Tell Us About Your <span>Requirement</span></h2>
            <p class="section-subtitle mx-auto">Fill in the details below and our team will reach out to discuss suitable financial solutions.</p>
          </div>
          <form data-validate data-endpoint="mail/send-apply.php" novalidate id="enquiryForm">
            <!-- Honeypot: hidden from real visitors, bots tend to fill every field -->
            <div class="d-none" aria-hidden="true">
              <label for="applyWebsite">Leave this field blank</label>
              <input type="text" id="applyWebsite" name="website" tabindex="-1" autocomplete="off">
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label for="fullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="fullName" name="fullName" required>
                <div class="invalid-feedback">Please enter your full name.</div>
              </div>
              <div class="col-md-6">
                <label for="mobile" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="mobile" name="mobile" data-validate="mobile" placeholder="10-digit mobile number" required>
                <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
              </div>
              <div class="col-md-6">
                <label for="loanType" class="form-label">Loan Type</label>
                <select class="form-select" id="loanType" name="loanType" required>
                  <option value="" selected disabled>Select loan type</option>
                  <option value="Personal Loan">Personal Loan</option>
                  <option value="Unsecured Business Loan">Unsecured Business Loan</option>
                  <option value="Housing Loan">Housing Loan</option>
                  <option value="Loan Against Property">Loan Against Property (LAP)</option>
                  <option value="Car Loan">Car Loan &ndash; New &amp; Used</option>
                  <option value="Short Term Finance">Short Term Finance</option>
                  <option value="Channel Finance">Channel Finance</option>
                </select>
                <div class="invalid-feedback">Please select a loan type.</div>
              </div>
              <div class="col-md-6">
                <label for="loanAmount" class="form-label">Required Loan Amount (&#8377;)</label>
                <input type="number" class="form-control" id="loanAmount" name="loanAmount" data-validate="amount" min="1" placeholder="e.g. 500000" required>
                <div class="invalid-feedback">Please enter a valid loan amount.</div>
              </div>
              <div class="col-md-6">
                <label for="employmentType" class="form-label">Employment Type</label>
                <select class="form-select" id="employmentType" name="employmentType" required>
                  <option value="" selected disabled>Select employment type</option>
                  <option value="Salaried">Salaried</option>
                  <option value="Self-Employed Professional">Self-Employed Professional</option>
                  <option value="Business Owner">Business Owner</option>
                  <option value="Other">Other</option>
                </select>
                <div class="invalid-feedback">Please select your employment type.</div>
              </div>
              <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
                <div class="invalid-feedback">Please enter your city.</div>
              </div>
              <div class="col-12">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Tell us more about your requirement (optional)"></textarea>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-gold w-100">Submit Enquiry</button>
                <p class="text-center text-muted mt-3 mb-0" style="font-size:0.82rem;">Submission of an enquiry does not guarantee loan approval or disbursal. See our <a href="legal/disclaimer.php">disclaimer</a> for details.</p>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php $includeCalculator = false; include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
