<?php
/**
 * SMTP + mail settings for the contact form.
 *
 * ── Setup ────────────────────────────────────────────────────────────────
 * 1. Copy this file to `mail/config.php` (that file is gitignored — it
 *    holds a real secret once filled in and must never be committed).
 * 2. Fill in 'smtp_username'/'smtp_password' below with a Gmail App
 *    Password for the sending account:
 *      a. Sign in to that Gmail account at https://myaccount.google.com/security
 *      b. Turn on 2-Step Verification if it isn't already on (required for
 *         App Passwords).
 *      c. Go to https://myaccount.google.com/apppasswords
 *      d. Create a new App Password (name it e.g. "Finzo Website"), Google
 *         will show a 16-character code like "abcd efgh ijkl mnop".
 *      e. Paste that code below as 'smtp_password' (spaces don't matter).
 * Do NOT use the normal Gmail account password here — Google blocks that
 * for SMTP and it will fail.
 *
 * Keep 'smtp_username' and 'from_email' as the SAME address — Gmail may
 * reject or rewrite the sender otherwise.
 * ─────────────────────────────────────────────────────────────────────────
 */

return [
    // Outgoing SMTP server (Gmail's, since the company inbox is a Gmail address)
    'smtp_host'     => 'smtp.gmail.com',
    'smtp_port'     => 587,
    'smtp_secure'   => 'tls', // STARTTLS

    // The Gmail account the site sends FROM (also the inbox that receives enquiries)
    'smtp_username' => 'finzofinancialservices26@gmail.com',
    'smtp_password' => '', // <-- paste the 16-character Google App Password here (see steps above)

    // "From" identity shown to recipients
    'from_email'    => 'finzofinancialservices26@gmail.com',
    'from_name'     => 'Finzo Financial Services Website',

    // Where enquiry notifications are delivered
    'to_email'      => 'finzofinancialservices26@gmail.com',
    'to_name'       => 'Finzo Financial Services',

    // Company details reused inside the email templates
    'company_name'    => 'Finzo Financial Services',
    'company_phone'   => '+91 9944 270 207',
    'company_email'   => 'finzofinancialservices26@gmail.com',
    'company_address' => 'No. 1/1282/A, Vasanveli 7th Cross, Nachikurichi, Trichy - 620102',
    'site_url'        => 'http://localhost/finzo_website/',
];
