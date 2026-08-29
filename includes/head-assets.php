<?php
/**
 * Shared <head> asset links (fonts, Bootstrap, custom CSS).
 * Expects $base ('' for root pages, '../' for one-level-deep pages) to be
 * set by the including page before this file is included.
 */
if (!isset($base)) { $base = ''; }
?>
<link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Ccircle cx='32' cy='32' r='30' fill='%23071B49' stroke='%23D4AF37' stroke-width='3'/%3E%3Ctext x='32' y='42' font-size='30' font-family='Georgia,serif' font-weight='700' fill='%23D4AF37' text-anchor='middle'%3ES%3C/text%3E%3C/svg%3E">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?php echo $base; ?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo $base; ?>assets/css/responsive.css">
<link rel="stylesheet" href="<?php echo $base; ?>assets/css/animations.css">
