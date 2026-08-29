<?php
/**
 * Auto-reply email — sent back to the person who submitted the contact
 * form, confirming receipt. $data keys: name, mobile, email, message.
 */

require_once __DIR__ . '/layout.php';

if (!function_exists('finzo_autoreply_email_html')) {
    function finzo_autoreply_email_html(array $config, array $data): string
    {
        $firstName = htmlspecialchars(trim(explode(' ', trim($data['name']))[0] ?? $data['name']));
        $name      = htmlspecialchars($data['name']);
        $mobile    = htmlspecialchars($data['mobile']);
        $email     = htmlspecialchars($data['email']);
        $message   = nl2br(htmlspecialchars($data['message']), false);
        $phone     = htmlspecialchars($config['company_phone']);
        $companyEmail = htmlspecialchars($config['company_email']);

        $rows = ''
            . finzo_email_detail_row('Full Name', $name, false)
            . finzo_email_detail_row('Mobile', $mobile, true)
            . finzo_email_detail_row('Email', $email, false)
            . finzo_email_detail_row('Your Message', $message !== '' ? $message : '<span style="color:#8a95ad;">(no message provided)</span>', true);

        $body = <<<HTML
<span style="display:inline-block; background-color:rgba(212,175,55,0.12); border:1px solid rgba(212,175,55,0.4); color:#b9922c; font-family:Arial, sans-serif; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; padding:6px 14px; border-radius:50px; margin-bottom:16px;">Enquiry Received</span>

<h1 style="margin:0 0 8px; font-family:Georgia, 'Times New Roman', serif; font-size:22px; color:#071B49;">Thank you, {$firstName}</h1>
<p style="margin:0 0 24px; font-family:Arial, sans-serif; font-size:14px; color:#5a6478; line-height:1.6;">
  We've received your enquiry and a member of the Finzo Financial Services team will get in touch with you shortly. For your records, here's a copy of what you submitted:
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #eef0f4; border-radius:10px; overflow:hidden; margin-bottom:28px;">
{$rows}
</table>

<p style="margin:0 0 24px; font-family:Arial, sans-serif; font-size:14px; color:#5a6478; line-height:1.6;">
  Need to reach us sooner? Call us at <strong style="color:#071B49;">{$phone}</strong> or reply directly to this email.
</p>

<table role="presentation" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border-radius:8px; border:2px solid #071B49;">
      <a href="tel:+919944270207" style="display:inline-block; padding:11px 24px; font-family:Arial, sans-serif; font-size:14px; font-weight:700; color:#071B49; text-decoration:none;">Call {$phone}</a>
    </td>
  </tr>
</table>

<p style="margin:24px 0 0; font-family:Arial, sans-serif; font-size:12px; color:#8a95ad; line-height:1.6;">
  This is an automated acknowledgement — please do not treat it as confirmation of loan approval or any specific offer. If you did not submit this enquiry, you can safely ignore this email or contact us at {$companyEmail}.
</p>
HTML;

        return finzo_email_shell($config, "Thanks for contacting Finzo Financial Services — we've received your enquiry", $body);
    }
}
