<?php
/**
 * Internal notification email — sent to Finzo's inbox when someone submits
 * the contact form. $data keys: name, mobile, email, message, submittedAt.
 */

require_once __DIR__ . '/layout.php';

if (!function_exists('finzo_notification_email_html')) {
    function finzo_notification_email_html(array $config, array $data): string
    {
        $name    = htmlspecialchars($data['name']);
        $mobile  = htmlspecialchars($data['mobile']);
        $email   = htmlspecialchars($data['email']);
        $message = nl2br(htmlspecialchars($data['message']), false);
        $when    = htmlspecialchars($data['submittedAt']);
        $mailtoHref = 'mailto:' . rawurlencode($data['email']);

        $rows = ''
            . finzo_email_detail_row('Full Name', $name, false)
            . finzo_email_detail_row('Mobile', $mobile, true)
            . finzo_email_detail_row('Email', $email, false)
            . finzo_email_detail_row('Message', $message !== '' ? $message : '<span style="color:#8a95ad;">(no message provided)</span>', true)
            . finzo_email_detail_row('Submitted', $when, false);

        $body = <<<HTML
<span style="display:inline-block; background-color:rgba(212,175,55,0.12); border:1px solid rgba(212,175,55,0.4); color:#b9922c; font-family:Arial, sans-serif; font-size:11px; font-weight:700; letter-spacing:1px; text-transform:uppercase; padding:6px 14px; border-radius:50px; margin-bottom:16px;">New Website Enquiry</span>

<h1 style="margin:0 0 8px; font-family:Georgia, 'Times New Roman', serif; font-size:22px; color:#071B49;">You've received a new enquiry</h1>
<p style="margin:0 0 24px; font-family:Arial, sans-serif; font-size:14px; color:#5a6478; line-height:1.6;">Submitted via the Contact page on the Finzo Financial Services website. Details are below.</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #eef0f4; border-radius:10px; overflow:hidden; margin-bottom:28px;">
{$rows}
</table>

<table role="presentation" cellpadding="0" cellspacing="0">
  <tr>
    <td style="border-radius:8px; background-color:#D4AF37;">
      <a href="{$mailtoHref}" style="display:inline-block; padding:12px 26px; font-family:Arial, sans-serif; font-size:14px; font-weight:700; color:#03112F; text-decoration:none;">Reply to {$name}</a>
    </td>
  </tr>
</table>
HTML;

        return finzo_email_shell($config, "New enquiry from {$name} via the Finzo website contact form", $body);
    }
}
