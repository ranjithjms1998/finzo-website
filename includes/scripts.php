<?php
/**
 * Shared bottom-of-body scripts.
 * Expects (set by the including page before this file is included):
 *   $base             - '' for root pages, '../' for one-level-deep pages.
 *   $includeCalculator - true only on pages that use the EMI calculator
 *                         (index.php, emi-calculator.php). Defaults to false.
 */
if (!isset($base)) { $base = ''; }
if (!isset($includeCalculator)) { $includeCalculator = false; }
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo $base; ?>assets/js/main.js"></script>
<?php if ($includeCalculator): ?>
<script src="<?php echo $base; ?>assets/js/calculator.js"></script>
<?php endif; ?>
<script src="<?php echo $base; ?>assets/js/validation.js"></script>
<script src="<?php echo $base; ?>assets/js/animations.js"></script>
