<?php
/**
 * Shared branded HTML email shell (navy/gold, table-based for email-client
 * compatibility). Every template wraps its content with finzo_email_shell().
 */

if (!function_exists('finzo_email_shell')) {
    /**
     * @param array  $config    The array returned by mail/config.php
     * @param string $preheader Short hidden preview text shown in inbox lists
     * @param string $bodyHtml  Inner content HTML (already-safe/escaped by caller)
     */
    function finzo_email_shell(array $config, string $preheader, string $bodyHtml): string
    {
        $company = htmlspecialchars($config['company_name']);
        $phone   = htmlspecialchars($config['company_phone']);
        $email   = htmlspecialchars($config['company_email']);
        $address = htmlspecialchars($config['company_address']);
        $year    = date('Y');

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{$company}</title>
</head>
<body style="margin:0; padding:0; background-color:#F5F7FA; font-family:'Segoe UI', Arial, Helvetica, sans-serif;">
  <div style="display:none; max-height:0; overflow:hidden; opacity:0; font-size:1px; line-height:1px; color:#F5F7FA;">
    {$preheader}
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F5F7FA; padding:32px 16px;">
    <tr>
      <td align="center">
        <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#FFFFFF; border-radius:14px; overflow:hidden; border:1px solid #e7e9ee;">

          <!-- Header -->
          <tr>
            <td style="background-color:#071B49; padding:28px 32px; border-bottom:3px solid #D4AF37;">
              <table role="presentation" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="width:46px; height:46px; border-radius:50%; background-color:#0D2B63; border:2px solid #D4AF37; text-align:center; vertical-align:middle;">
                    <span style="font-family:Georgia, 'Times New Roman', serif; font-weight:700; font-size:20px; color:#D4AF37; line-height:46px;">S</span>
                  </td>
                  <td style="padding-left:14px; vertical-align:middle;">
                    <span style="display:block; font-family:Georgia, 'Times New Roman', serif; font-weight:700; font-size:19px; color:#FFFFFF; letter-spacing:0.5px;">FINZO</span>
                    <span style="display:block; font-family:Arial, sans-serif; font-size:10px; font-weight:700; letter-spacing:2px; color:#D4AF37; text-transform:uppercase;">Financial Services</span>
                  </td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding:36px 32px; color:#071B49;">
              {$bodyHtml}
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color:#03112F; padding:26px 32px;">
              <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td style="font-family:Arial, sans-serif; font-size:13px; color:#FFFFFF; font-weight:700; padding-bottom:8px;">
                    {$company}
                  </td>
                </tr>
                <tr>
                  <td style="font-family:Arial, sans-serif; font-size:12px; color:#cdd6e8; line-height:1.7;">
                    {$phone} &nbsp;&bull;&nbsp; {$email}<br>
                    {$address}
                  </td>
                </tr>
                <tr>
                  <td style="font-family:Arial, sans-serif; font-size:11px; color:#8a95ad; line-height:1.6; padding-top:16px; border-top:1px solid rgba(255,255,255,0.1); margin-top:16px;">
                    Finzo Financial Services facilitates access to financial solutions and loan-related assistance. Loan approval, interest rates, tenure, fees and other terms are subject to the policies, eligibility criteria and approval processes of the respective financial institution/lender. Submission of an enquiry does not guarantee loan approval or disbursal.
                  </td>
                </tr>
                <tr>
                  <td style="font-family:Arial, sans-serif; font-size:11px; color:#5a6478; padding-top:14px;">
                    &copy; {$year} {$company}. All Rights Reserved.
                  </td>
                </tr>
              </table>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    }

    /** One "label: value" row for the details table used in both templates. */
    function finzo_email_detail_row(string $label, string $value, bool $zebra = false): string
    {
        $bg = $zebra ? '#F5F7FA' : '#FFFFFF';
        $label = htmlspecialchars($label);
        // $value is pre-escaped by the caller (may contain <br> for multiline messages)
        return <<<HTML
<tr>
  <td style="background-color:{$bg}; padding:12px 16px; font-family:Arial, sans-serif; font-size:12px; font-weight:700; color:#8a95ad; text-transform:uppercase; letter-spacing:0.5px; width:150px; vertical-align:top; border-bottom:1px solid #eef0f4;">{$label}</td>
  <td style="background-color:{$bg}; padding:12px 16px; font-family:Arial, sans-serif; font-size:14px; color:#071B49; vertical-align:top; border-bottom:1px solid #eef0f4;">{$value}</td>
</tr>
HTML;
    }
}
