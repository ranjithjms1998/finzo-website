<?php
/**
 * Apply Now / loan enquiry form submission endpoint.
 * Receives a POST from apply-now.php's form (fields: fullName, mobile,
 * email, loanType, loanAmount, employmentType, city, message, plus a
 * hidden honeypot field "website"), validates everything server-side,
 * sends a branded notification email to Finzo's inbox and a branded
 * auto-reply to the applicant, and responds with JSON that
 * assets/js/validation.js reads to show a success/error message.
 */

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/db.php';
require __DIR__ . '/templates/notification-apply-email.php';
require __DIR__ . '/templates/autoreply-apply-email.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

function finzo_json_response(bool $success, string $message, int $status = 200): void
{
    http_response_code($status);
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    finzo_json_response(false, 'Invalid request method.', 405);
}

// --- Honeypot: real users never see or fill this hidden field ---
if (!empty($_POST['website'])) {
    finzo_json_response(true, 'Thank you! Your enquiry has been received.');
}

// --- Gather + trim input ---
$name       = trim((string)($_POST['fullName'] ?? ''));
$mobile     = trim((string)($_POST['mobile'] ?? ''));
$email      = trim((string)($_POST['email'] ?? ''));
$loanType   = trim((string)($_POST['loanType'] ?? ''));
$loanAmount = trim((string)($_POST['loanAmount'] ?? ''));
$employment = trim((string)($_POST['employmentType'] ?? ''));
$city       = trim((string)($_POST['city'] ?? ''));
$message    = trim((string)($_POST['message'] ?? ''));

// --- Server-side validation (mirrors the <select> options in apply-now.php
//     and the rules in assets/js/validation.js) ---
$allowedLoanTypes = [
    'Personal Loan', 'Unsecured Business Loan', 'Housing Loan',
    'Loan Against Property', 'Car Loan', 'Short Term Finance', 'Channel Finance',
];
$allowedEmploymentTypes = ['Salaried', 'Self-Employed Professional', 'Business Owner', 'Other'];

$errors = [];

if ($name === '' || mb_strlen($name) > 150) {
    $errors[] = 'Please enter a valid full name.';
}
if (!preg_match('/^[6-9]\d{9}$/', $mobile)) {
    $errors[] = 'Please enter a valid 10-digit mobile number.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}
if (!in_array($loanType, $allowedLoanTypes, true)) {
    $errors[] = 'Please select a valid loan type.';
}
if (!is_numeric($loanAmount) || (float)$loanAmount <= 0) {
    $errors[] = 'Please enter a valid loan amount.';
}
if (!in_array($employment, $allowedEmploymentTypes, true)) {
    $errors[] = 'Please select a valid employment type.';
}
if ($city === '' || mb_strlen($city) > 100) {
    $errors[] = 'Please enter a valid city.';
}
if (mb_strlen($message) > 3000) {
    $errors[] = 'Message is too long.';
}

if (!empty($errors)) {
    finzo_json_response(false, implode(' ', $errors), 422);
}

// --- Persist to the admin panel's database (best-effort — a DB outage
//     should never block the enquiry from still being emailed) ---
$db = finzo_db_connect();
if ($db !== null) {
    try {
        $loanAmountFloat = (float) $loanAmount;
        $stmt = $db->prepare(
            'INSERT INTO leads (full_name, mobile, email, loan_type, loan_amount, employment_type, city, message, status, source)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, "new", "apply_now")'
        );
        $stmt->bind_param('ssssdsss', $name, $mobile, $email, $loanType, $loanAmountFloat, $employment, $city, $message);
        $stmt->execute();
        $leadId = $db->insert_id;
        $stmt->close();

        if ($leadId) {
            $historyStmt = $db->prepare(
                'INSERT INTO lead_status_history (lead_id, old_status, new_status, note) VALUES (?, NULL, "new", "Lead captured from website Apply Now form")'
            );
            $historyStmt->bind_param('i', $leadId);
            $historyStmt->execute();
            $historyStmt->close();
        }
    } catch (\Throwable $e) {
        error_log('[Finzo apply-now form] DB insert failed: ' . $e->getMessage());
    }
}

$config = require __DIR__ . '/config.php';

// Google displays App Passwords with spaces for readability — strip them
// in case they were pasted verbatim.
$config['smtp_password'] = str_replace(' ', '', (string)$config['smtp_password']);

if (empty($config['smtp_password'])) {
    error_log('[Finzo apply-now form] smtp_password is empty in mail/config.php — email not sent.');
    finzo_json_response(
        false,
        "Sorry, our online form isn't accepting submissions right now. Please call us at {$config['company_phone']} or email {$config['company_email']} directly.",
        503
    );
}

$data = [
    'name'           => $name,
    'mobile'         => $mobile,
    'email'          => $email,
    'loanType'       => $loanType,
    'loanAmount'     => (float)$loanAmount,
    'employmentType' => $employment,
    'city'           => $city,
    'message'        => $message,
    'submittedAt'    => date('d M Y, h:i A'),
];

try {
    // --- 1. Notification email to Finzo's inbox ---
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = $config['smtp_host'];
    $mail->SMTPAuth   = true;
    $mail->Username    = $config['smtp_username'];
    $mail->Password    = $config['smtp_password'];
    $mail->SMTPSecure  = $config['smtp_secure'];
    $mail->Port        = $config['smtp_port'];

    $mail->setFrom($config['from_email'], $config['from_name']);
    $mail->addAddress($config['to_email'], $config['to_name']);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = "New {$loanType} Application from {$name}";
    $mail->Body    = finzo_apply_notification_email_html($config, $data);
    $mail->AltBody = "New {$loanType} enquiry from {$name}\nMobile: {$mobile}\nEmail: {$email}\nLoan Amount: {$loanAmount}\nEmployment: {$employment}\nCity: {$city}\nMessage: {$message}\nSubmitted: {$data['submittedAt']}";

    $mail->send();

    // --- 2. Best-effort auto-reply to the applicant (failure here shouldn't fail the request) ---
    try {
        $reply = new PHPMailer(true);
        $reply->isSMTP();
        $reply->Host       = $config['smtp_host'];
        $reply->SMTPAuth   = true;
        $reply->Username    = $config['smtp_username'];
        $reply->Password    = $config['smtp_password'];
        $reply->SMTPSecure  = $config['smtp_secure'];
        $reply->Port        = $config['smtp_port'];

        $reply->setFrom($config['from_email'], $config['from_name']);
        $reply->addAddress($email, $name);

        $reply->isHTML(true);
        $reply->Subject = "We've received your {$loanType} enquiry — Finzo Financial Services";
        $reply->Body    = finzo_apply_autoreply_email_html($config, $data);
        $reply->AltBody = "Thank you for your {$loanType} enquiry with Finzo Financial Services. We have received it and will be in touch shortly.";

        $reply->send();
    } catch (PHPMailerException $autoReplyError) {
        error_log('[Finzo apply-now form] Auto-reply failed: ' . $autoReplyError->getMessage());
    }

    finzo_json_response(true, 'Thank you! Your enquiry has been received. Our team will contact you shortly.');
} catch (PHPMailerException $e) {
    error_log('[Finzo apply-now form] Send failed: ' . $e->getMessage());
    finzo_json_response(
        false,
        "Sorry, something went wrong sending your enquiry. Please call us at {$config['company_phone']} or try again shortly.",
        500
    );
}
