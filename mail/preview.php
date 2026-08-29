<?php
/**
 * Local-only preview harness: renders the email templates with sample data
 * so they can be viewed in a browser without actually sending mail.
 * Not linked from anywhere in the site nav — safe to leave, but fine to
 * delete once you're happy with the designs.
 *
 * Usage: mail/preview.php?type=notification|autoreply|apply-notification|apply-autoreply
 */

// Dev-only safety guard: refuse to render outside localhost, in case this
// file is ever left in place on a public deployment.
$remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
if (!in_array($remoteAddr, ['127.0.0.1', '::1'], true)) {
    http_response_code(404);
    exit('Not found.');
}

require __DIR__ . '/templates/notification-email.php';
require __DIR__ . '/templates/autoreply-email.php';
require __DIR__ . '/templates/notification-apply-email.php';
require __DIR__ . '/templates/autoreply-apply-email.php';

$config = require __DIR__ . '/config.php';

$contactSample = [
    'name'        => 'Arun Kumar',
    'mobile'      => '9876543210',
    'email'       => 'arun.kumar@example.com',
    'message'     => "Hi, I'm looking for a personal loan of about 5 lakhs for a family medical expense. Please let me know the process.",
    'submittedAt' => date('d M Y, h:i A'),
];

$applySample = [
    'name'           => 'Priya Ramesh',
    'mobile'         => '9876500001',
    'email'          => 'priya.ramesh@example.com',
    'loanType'       => 'Housing Loan',
    'loanAmount'     => 2500000,
    'employmentType' => 'Salaried',
    'city'           => 'Trichy',
    'message'        => 'Looking to buy a 2BHK apartment, would like to understand the process and eligibility.',
    'submittedAt'    => date('d M Y, h:i A'),
];

$which = $_GET['type'] ?? 'notification';

switch ($which) {
    case 'autoreply':
        echo finzo_autoreply_email_html($config, $contactSample);
        break;
    case 'apply-notification':
        echo finzo_apply_notification_email_html($config, $applySample);
        break;
    case 'apply-autoreply':
        echo finzo_apply_autoreply_email_html($config, $applySample);
        break;
    default:
        echo finzo_notification_email_html($config, $contactSample);
}
