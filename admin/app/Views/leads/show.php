<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<?php
if (! function_exists('finzo_format_inr')) {
    function finzo_format_inr($amount): string
    {
        if (class_exists('NumberFormatter')) {
            $fmt = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
            $fmt->setAttribute(NumberFormatter::FRACTION_DIGITS, 0);
            return '₹' . $fmt->format((float) $amount);
        }
        return '₹' . number_format((float) $amount, 0);
    }
}
?>

<div class="mb-3">
  <a href="<?= site_url('leads') ?>" class="text-muted small"><i class="bi bi-arrow-left"></i> Back to Leads</a>
</div>

<div class="row g-3">
  <div class="col-lg-7">
    <div class="panel-card">
      <h2 class="mb-3"><?= esc($lead['full_name']) ?></h2>

      <div class="detail-row">
        <span class="label">Full Name</span>
        <span class="value"><?= esc($lead['full_name']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Mobile</span>
        <span class="value"><a href="tel:<?= esc($lead['mobile'], 'attr') ?>"><?= esc($lead['mobile']) ?></a></span>
      </div>
      <div class="detail-row">
        <span class="label">Email</span>
        <span class="value"><a href="mailto:<?= esc($lead['email'], 'attr') ?>"><?= esc($lead['email']) ?></a></span>
      </div>
      <div class="detail-row">
        <span class="label">Loan Type</span>
        <span class="value"><?= esc($lead['loan_type']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Loan Amount</span>
        <span class="value"><?= finzo_format_inr($lead['loan_amount']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Employment Type</span>
        <span class="value"><?= esc($lead['employment_type']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">City</span>
        <span class="value"><?= esc($lead['city']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Message</span>
        <span class="value">
          <?php if (! empty($lead['message'])) : ?>
            <?= nl2br(esc($lead['message'])) ?>
          <?php else : ?>
            <span class="text-muted">(no message provided)</span>
          <?php endif; ?>
        </span>
      </div>
      <div class="detail-row">
        <span class="label">Source</span>
        <span class="value"><?= esc($lead['source']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Received</span>
        <span class="value"><?= date('d M Y, h:i A', strtotime($lead['created_at'])) ?></span>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="panel-card mb-3">
      <h5 class="mb-3">Update Status</h5>
      <form method="post" action="<?= site_url('leads/' . $lead['id'] . '/status') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label small" for="status">Status</label>
          <select class="form-select form-select-sm" id="status" name="status">
            <?php foreach ($statuses as $key => $label) : ?>
              <option value="<?= esc($key) ?>" <?= $lead['status'] === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label small" for="note">Note</label>
          <textarea class="form-control form-control-sm" id="note" name="note" rows="3" placeholder="Add a note about this update (optional)"></textarea>
        </div>
        <button type="submit" class="btn btn-gold btn-sm">Update Status</button>
      </form>
    </div>

    <div class="panel-card">
      <h5 class="mb-3">Status History</h5>
      <?php if (empty($history)) : ?>
        <p class="text-muted mb-0 small">No status changes recorded yet.</p>
      <?php else : ?>
        <?php foreach ($history as $row) : ?>
          <div class="timeline-item">
            <span class="dot"></span>
            <div>
              <div class="fw-semibold small">
                <?php if (! empty($row['old_status'])) : ?>
                  <?= esc($statuses[$row['new_status']] ?? $row['new_status']) ?>
                  <span class="text-muted fw-normal">(was <?= esc($statuses[$row['old_status']] ?? $row['old_status']) ?>)</span>
                <?php else : ?>
                  Set to <?= esc($statuses[$row['new_status']] ?? $row['new_status']) ?>
                <?php endif; ?>
              </div>
              <?php if (! empty($row['note'])) : ?>
                <div class="small text-muted mt-1"><?= nl2br(esc($row['note'])) ?></div>
              <?php endif; ?>
              <div class="small text-muted mt-1">
                <?= esc($row['changed_by'] !== null ? ($adminNames[$row['changed_by']] ?? 'Unknown') : 'System') ?>
                &middot; <?= date('d M Y, h:i A', strtotime($row['created_at'])) ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
