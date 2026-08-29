<?php
$base = '';
$active = 'contact';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | Finzo Financial Services</title>
<meta name="description" content="Get in touch with Finzo Financial Services. Call +91 9944 270 207, email finzofinancialservices26@gmail.com, or visit our office in Trichy.">
<meta property="og:type" content="website">
<meta property="og:title" content="Contact Us | Finzo Financial Services">
<meta property="og:description" content="Reach out to Finzo Financial Services for financial and loan assistance.">
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body>

<?php include __DIR__ . '/includes/header.php'; ?>

<header class="page-hero">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Contact</li>
      </ol>
    </nav>
    <h1>Contact Finzo Financial Services</h1>
    <p>We're here to help. Reach out to us through any of the channels below, or send us your enquiry directly.</p>
  </div>
</header>

<section class="section-padding">
  <div class="container">
    <div class="row g-4 mb-5">
      <div class="col-md-4" data-aos="fade-up">
        <div class="contact-card">
          <span class="icon-box"><i class="bi bi-telephone"></i></span>
          <h4>Call Us</h4>
          <p>+91 9944 270 207</p>
          <a href="tel:+919944270207" class="btn btn-outline-navy btn-sm-pill">Call Now</a>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-delay="1">
        <div class="contact-card">
          <span class="icon-box"><i class="bi bi-envelope"></i></span>
          <h4>Email Us</h4>
          <p>finzofinancialservices26@gmail.com</p>
          <a href="mailto:finzofinancialservices26@gmail.com" class="btn btn-outline-navy btn-sm-pill">Email Now</a>
        </div>
      </div>
      <div class="col-md-4" data-aos="fade-up" data-delay="2">
        <div class="contact-card">
          <span class="icon-box"><i class="bi bi-geo-alt"></i></span>
          <h4>Visit Us</h4>
          <p>No. 1/1282/A, Vasanveli 7th Cross, Nachikurichi, Trichy - 620102</p>
          <a href="#map" class="btn btn-outline-navy btn-sm-pill">View Map</a>
        </div>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-lg-6" data-aos="fade-up">
        <span class="section-eyebrow">Send an Enquiry</span>
        <h2 class="section-title mb-4">Get in <span>Touch</span></h2>
        <div class="finzo-form-card">
          <form data-validate data-endpoint="mail/send-contact.php" novalidate>
            <!-- Honeypot: hidden from real visitors, bots tend to fill every field -->
            <div class="d-none" aria-hidden="true">
              <label for="cWebsite">Leave this field blank</label>
              <input type="text" id="cWebsite" name="website" tabindex="-1" autocomplete="off">
            </div>
            <div class="row g-3">
              <div class="col-md-6">
                <label for="cFullName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="cFullName" name="fullName" required>
                <div class="invalid-feedback">Please enter your full name.</div>
              </div>
              <div class="col-md-6">
                <label for="cMobile" class="form-label">Mobile Number</label>
                <input type="tel" class="form-control" id="cMobile" name="mobile" data-validate="mobile" required>
                <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
              </div>
              <div class="col-md-12">
                <label for="cEmail" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="cEmail" name="email" required>
                <div class="invalid-feedback">Please enter a valid email address.</div>
              </div>
              <div class="col-md-12">
                <label for="cMessage" class="form-label">Message</label>
                <textarea class="form-control" id="cMessage" name="message" rows="4" required></textarea>
                <div class="invalid-feedback">Please enter your message.</div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-gold w-100">Submit Enquiry</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-6" data-aos="fade-up" data-delay="1" id="map">
        <span class="section-eyebrow">Our Location</span>
        <h2 class="section-title mb-4">Find <span>Us Here</span></h2>
        <div class="map-embed mb-3">
          <iframe src="https://www.google.com/maps?q=Nachikurichi,Trichy&output=embed" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Finzo Financial Services location map" aria-label="Map showing the approximate location of Finzo Financial Services in Nachikurichi, Trichy"></iframe>
        </div>
        <!-- <div class="feature-block">
          <span class="icon-box"><i class="bi bi-geo-alt"></i></span>
          <div>
            <h4>Registered Office</h4>
            <p>No. 1/1282/A, Vasanveli 7th Cross, Nachikurichi, Trichy - 620102</p>
          </div>
        </div> -->
        <div class="md-card">
          <span class="icon-box"><i class="bi bi-person-vcard"></i></span>
          <div>
            <strong>P. Sathishkumar</strong>
            <span>Managing Director, Finzo Financial Services</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>

<?php $includeCalculator = false; include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
