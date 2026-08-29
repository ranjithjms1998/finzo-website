# Finzo Financial Services — Website

A premium, multi-page marketing and lead-generation website for **Finzo Financial Services**, a Trichy-based financial and loan assistance provider. The site presents the company's services (personal, business, housing, property and vehicle loan assistance) and captures enquiries through contact and application forms.

## Tech Stack

- **HTML5** — semantic markup across all pages
- **PHP** (includes only — no database, no framework) — every page is a `.php` file that pulls in a shared header, footer and `<head>` asset block via `include`, so the navbar/footer only need to be edited in one place. See "Shared Header/Footer" below.
- **CSS3** — custom stylesheet with CSS variables (no preprocessor)
- **Vanilla JavaScript** — no framework, no build step
- **Bootstrap 5.3.3** — via jsDelivr CDN (layout grid, navbar, accordion, forms)
- **Bootstrap Icons 1.11.3** — via jsDelivr CDN
- **Google Fonts** — Playfair Display (headings) + Inter (body), via Google Fonts CDN
- **PHPMailer** (via Composer) — sends the contact form's emails over SMTP; see "Contact Form Email Integration"

No frontend build tools are used. The runtime requirements are a PHP-capable web server (PHP 8+ recommended) to process the `include` statements, and Composer to install PHPMailer (`composer install`, already run — see `vendor/`) — see "Running Locally".

## Folder Structure

```
finzo_website/
├── index.php                    Home page
├── about.php                    About Us
├── services.php                 Services overview
├── emi-calculator.php           EMI calculator tool
├── faq.php                      FAQ
├── contact.php                  Contact page
├── apply-now.php                Loan application / enquiry form
├── includes/                    Shared page partials, included by every page
│   ├── head-assets.php          Favicon, Google Fonts, Bootstrap CSS, custom CSS links
│   ├── header.php                Sticky navbar (active-link highlighting via $active)
│   ├── footer.php                Footer + back-to-top button
│   └── scripts.php               Bottom <script> tags (Bootstrap JS, main.js, etc.)
├── services/                    Individual service detail pages
│   ├── personal-loan.php
│   ├── business-loan.php
│   ├── housing-loan.php
│   ├── loan-against-property.php
│   ├── car-loan.php
│   ├── short-term-finance.php
│   └── channel-finance.php
├── legal/                       Legal / policy pages
│   ├── privacy-policy.php
│   ├── terms.php
│   └── disclaimer.php
├── mail/                        Contact form email sending (see "Contact Form
│                                  Email Integration" below) — config.php,
│                                  send-contact.php, preview.php, templates/
├── vendor/                      Composer dependencies (PHPMailer)
├── composer.json / composer.lock
├── assets/
│   ├── css/
│   │   ├── style.css             Core stylesheet (variables, components)
│   │   ├── responsive.css        Breakpoint / responsive overrides
│   │   └── animations.css        Scroll / entrance / hover animation styles
│   ├── js/
│   │   ├── main.js               Navbar scroll state, back-to-top, misc UI
│   │   ├── calculator.js         EMI calculator logic (index.php & emi-calculator.php only)
│   │   ├── validation.js         Client-side form validation + submission handler
│   │   └── animations.js         Scroll-triggered animation logic
│   ├── images/                   Reserved for local image assets (logo/hero/services/about
│   │                              subfolders exist but are currently empty — see note below)
│   └── icons/                    Reserved for local icon assets (currently unused —
│                                  icons are served via the Bootstrap Icons CDN font)
├── admin/                       Separate CodeIgniter 4 admin panel (leads, contact
│                                  messages, dashboard, reports) — see admin/README.md.
│                                  Independent app; the public site only shares a
│                                  MySQL database with it (via mail/db.php), never PHP code.
└── README.md
```

> Note: `services/` and `legal/` pages sit one folder below the project root, so their `include` paths and `$base` value differ from root pages — see below.

## Shared Header/Footer (PHP Includes)

Every page sets two variables *before* pulling in the shared partials:

```php
<?php
$base   = '';       // '' on root pages, '../' on pages inside services/ or legal/
$active = 'about';   // one of: home, about, services, emi, faq, contact, apply — or omit for none
?>
<!DOCTYPE html>
<html lang="en">
<head>
...
<?php include __DIR__ . '/includes/head-assets.php'; ?>
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

... page-specific content ...

<?php include __DIR__ . '/includes/footer.php'; ?>
<?php $includeCalculator = false; include __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
```

- `$base` is prefixed onto every link inside `header.php`/`footer.php`/`head-assets.php`/`scripts.php`, so those four files never need to know how deep the current page sits — the calling page tells them via `$base` (empty string at the root, `'../'` one level down in `services/` or `legal/`).
- `$active` controls which nav-link gets the `active` class in the navbar (and the Apply Now button, via `$active = 'apply'`).
- `$includeCalculator` (passed to `scripts.php`) controls whether `assets/js/calculator.js` is loaded — only `index.php` and `emi-calculator.php` need it.
- **To change the navbar or footer site-wide, edit `includes/header.php` or `includes/footer.php` once** — every page picks up the change automatically.

## Running Locally

This site now requires a **PHP-capable server** (the shared header/footer rely on `<?php include ?>`, so opening a `.php` file directly by double-clicking it will not work — the browser has no PHP interpreter).

**Recommended — Serve via XAMPP/Apache** (the project already lives under `htdocs`):
1. Ensure Apache (with PHP) is running in XAMPP.
2. Visit `http://localhost/finzo_website/` in your browser.

**Alternative — PHP's built-in server** (useful for quick local testing without XAMPP):
```
php -S localhost:8000
```
then visit `http://localhost:8000/`.

## Forms & Backend Integration Status

- **Both `contact.php` and `apply-now.php`'s forms are live** — each emails Finzo via SMTP (see "Email Integration" below).
- All form validation (required fields, phone/email format, etc.) lives in `assets/js/validation.js`. `submitFormData()` there checks each form's `data-endpoint` attribute: if present, it POSTs via `fetch()` to that PHP endpoint and shows whatever success/error message the endpoint returns; if absent, it falls back to a simulated-success behavior (not currently used by any form on the site, but kept as a safe default for any new form added later without its own endpoint yet).

## Email Integration

Both enquiry forms follow the same pattern — each posts (via `data-endpoint`) to its own PHP endpoint, which:

1. Re-validates every field server-side (never trusts the browser).
2. Rejects silently-but-successfully if a hidden honeypot field (`website`) is filled in — a basic bot filter.
3. Sends a **branded HTML notification email** to Finzo's inbox with the enquiry details and a "Reply to [Name]" button.
4. Sends a **branded HTML auto-reply** back to the person who submitted the form, confirming receipt and echoing back what they sent.
5. Returns JSON (`{success, message}`) that the frontend uses to show a success or error alert in place.

| Page | Form endpoint | Notification template | Auto-reply template |
|---|---|---|---|
| `contact.php` | `mail/send-contact.php` | `mail/templates/notification-email.php` | `mail/templates/autoreply-email.php` |
| `apply-now.php` | `mail/send-apply.php` | `mail/templates/notification-apply-email.php` (includes loan type/amount/employment/city) | `mail/templates/autoreply-apply-email.php` |

Both endpoints share the same branded shell (`mail/templates/layout.php` — the navy/gold header, details table, and footer) so the two email families look consistent while each carries the fields relevant to its form. Loan amounts are formatted Indian-style (e.g. `₹25,00,000`) via PHP's `intl` extension.

Mail is sent via [PHPMailer](https://github.com/PHPMailer/PHPMailer) (installed via Composer, see `composer.json` / `vendor/`) over Gmail's SMTP server, since the company inbox is a Gmail address.

**Files:**
```
mail/
├── config.php                          SMTP credentials + company details (see below)
├── send-contact.php                     POST endpoint for contact.php's form
├── send-apply.php                       POST endpoint for apply-now.php's form
├── preview.php                          Local-only: view all 4 email templates in a
│                                         browser with sample data, without sending
│                                         anything (localhost-only guard built in) —
│                                         ?type=notification|autoreply|apply-notification|apply-autoreply
└── templates/
    ├── layout.php                       Shared navy/gold branded email shell (header/footer)
    ├── notification-email.php           Contact form → Finzo's inbox
    ├── autoreply-email.php              Contact form → auto-reply to the enquirer
    ├── notification-apply-email.php     Apply Now form → Finzo's inbox (+ finzo_format_inr())
    └── autoreply-apply-email.php        Apply Now form → auto-reply to the applicant
```

**To activate real sending — fill in one value:**
1. Open `mail/config.php`.
2. Generate a Gmail **App Password** for the account in `smtp_username` (requires 2-Step Verification to be turned on first) at https://myaccount.google.com/apppasswords.
3. Paste that 16-character code into `'smtp_password'` in `mail/config.php` — spaces are fine, they're stripped automatically.

Until that's filled in, both forms still validate correctly but return a friendly "please call/email us instead" message rather than pretending to succeed — nothing is silently lost. Both endpoints share the same `mail/config.php`, so filling it in once activates both forms.

**Note on sender identity:** `smtp_username` (the account you authenticate as) should generally match `from_email`, or Gmail may reject or rewrite the sender. If they're different Gmail accounts, verify a test message actually lands in the intended inbox (check spam too) before relying on it.

**To preview the email designs** without sending anything, visit (while running locally):
- `http://localhost/finzo_website/mail/preview.php?type=notification`
- `http://localhost/finzo_website/mail/preview.php?type=autoreply`

`mail/config.php` holds a secret once filled in — don't publish it. If this project is ever put under version control, exclude it (and ideally `vendor/`, restorable via `composer install`) from the repo.

## Admin Panel

A separate CodeIgniter 4 application in `admin/` gives Finzo staff a login-protected dashboard to track every lead and contact message: status pipelines (New → Contacted → In Progress → Approved/Rejected → Closed for leads; New → Read → Responded → Closed for messages) with a full history timeline, charts, date-range reports, and CSV export. It's linked from the public site's footer ("Admin Login") and shares nothing with the public site except a MySQL database — see **`admin/README.md`** for full setup, access URL, and default credentials (change the password immediately after first login).

## Browser Support

Built for modern evergreen browsers (Chrome, Edge, Firefox, Safari — latest versions). No polyfills are included for legacy browsers such as Internet Explorer.

## Images

All photography currently used across the site (hero, about, service overview images) is hotlinked directly from Unsplash's CDN via full `https://images.unsplash.com/...` URLs in each page's `<img src>` — nothing is downloaded locally. Every image has a descriptive `alt` attribute. The `assets/images/` subfolders (`logo/`, `hero/`, `services/`, `about/`) are kept as placeholders in case local/optimized copies are added later; swapping to local files only requires updating the relevant `<img src>` paths.

## Credits

- **Images:** [Unsplash](https://unsplash.com)
- **Icons:** [Bootstrap Icons](https://icons.getbootstrap.com)
- **Fonts:** [Google Fonts](https://fonts.google.com) — Playfair Display, Inter
