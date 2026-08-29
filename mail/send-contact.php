<?php
/**
 * Contact form submission endpoint.
 * Receives a POST from contact.php's form (fields: fullName, mobile, email,
 * message, plus a hidden honeypot field "website"), validates everything
 * server-side, sends a branded notification email to Finzo's inbox and a
 * branded auto-reply to the enquirer, and responds with JSON that
 * assets/js/validation.js reads to show a success/error message.
 */

declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/db.php';
require __DIR__ . '/templates/notification-email.php';
require __DIR__ . '/templates/autoreply-email.php';

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
    // Pretend success so bots don't learn the field is being checked.
    finzo_json_response(true, 'Thank you! Your enquiry has been received.');
}

// --- Gather + trim input ---
$name    = trim((string)($_POST['fullName'] ?? ''));
$mobile  = trim((string)($_POST['mobile'] ?? ''));
$email   = trim((string)($_POST['email'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

// --- Server-side validation (mirrors assets/js/validation.js) ---
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
        $stmt = $db->prepare(
            'INSERT INTO contact_messages (full_name, mobile, email, message, status) VALUES (?, ?, ?, ?, "new")'
        );
        $stmt->bind_param('ssss', $name, $mobile, $email, $message);
        $stmt->execute();
        $stmt->close();
    } catch (\Throwable $e) {
        error_log('[Finzo contact form] DB insert failed: ' . $e->getMessage());
    }
}

$config = require __DIR__ . '/config.php';

// Google displays App Passwords with spaces for readability (e.g. "abcd efgh
// ijkl mnop") — strip them in case they were pasted verbatim.
$config['smtp_password'] = str_replace(' ', '', (string)$config['smtp_password']);

if (empty($config['smtp_password'])) {
    // Mail isn't configured yet (see mail/config.php) — fail clearly rather
    // than silently pretending the enquiry was sent.
    error_log('[Finzo contact form] smtp_password is empty in mail/config.php — email not sent.');
    finzo_json_response(
        false,
        "Sorry, our online form isn't accepting submissions right now. Please call us at {$config['company_phone']} or email {$config['company_email']} directly.",
        503
    );
}

$data = [
    'name'        => $name,
    'mobile'      => $mobile,
    'email'       => $email,
    'message'     => $message,
    'submittedAt' => date('d M Y, h:i A'),
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
    $mail->Subject = "New Website Enquiry from {$name}";
    $mail->Body    = finzo_notification_email_html($config, $data);
    $mail->AltBody = "New enquiry from {$name}\nMobile: {$mobile}\nEmail: {$email}\nMessage: {$message}\nSubmitted: {$data['submittedAt']}";

    $mail->send();

    // --- 2. Best-effort auto-reply to the enquirer (failure here shouldn't fail the request) ---
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
        $reply->Subject = "We've received your enquiry — Finzo Financial Services";
        $reply->Body    = finzo_autoreply_email_html($config, $data);
        $reply->AltBody = "Thank you for contacting Finzo Financial Services. We have received your enquiry and will be in touch shortly.";

        $reply->send();
    } catch (PHPMailerException $autoReplyError) {
        error_log('[Finzo contact form] Auto-reply failed: ' . $autoReplyError->getMessage());
    }

    finzo_json_response(true, 'Thank you! Your enquiry has been received. Our team will contact you shortly.');
} catch (PHPMailerException $e) {
    error_log('[Finzo contact form] Send failed: ' . $e->getMessage());
    finzo_json_response(
        false,
        "Sorry, something went wrong sending your enquiry. Please call us at {$config['company_phone']} or try again shortly.",
        500
    );
}
