<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="mb-3">
  <a href="<?= site_url('contacts') ?>" class="btn btn-outline-navy btn-sm"><i class="bi bi-arrow-left"></i> Back to Contact Messages</a>
</div>

<div class="row g-3">
  <div class="col-lg-7">
    <div class="panel-card h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Message Details</h2>
        <span class="badge-status <?= $statusBadge[$message['status']] ?? '' ?>"><?= esc($statuses[$message['status']] ?? $message['status']) ?></span>
      </div>

      <div class="d-flex gap-2 mb-3">
        <a href="tel:<?= esc($message['mobile'], 'attr') ?>" class="btn btn-outline-navy btn-sm"><i class="bi bi-telephone"></i> Call <?= esc($message['mobile']) ?></a>
        <a href="mailto:<?= esc($message['email'], 'attr') ?>" class="btn btn-outline-navy btn-sm"><i class="bi bi-envelope"></i> Email <?= esc($message['email']) ?></a>
      </div>

      <div class="detail-row">
        <span class="label">Full Name</span>
        <span class="value"><?= esc($message['full_name']) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Mobile</span>
        <span class="value"><a href="tel:<?= esc($message['mobile'], 'attr') ?>"><?= esc($message['mobile']) ?></a></span>
      </div>
      <div class="detail-row">
        <span class="label">Email</span>
        <span class="value"><a href="mailto:<?= esc($message['email'], 'attr') ?>"><?= esc($message['email']) ?></a></span>
      </div>
      <div class="detail-row">
        <span class="label">Received</span>
        <span class="value"><?= date('d M Y, h:i A', strtotime($message['created_at'])) ?></span>
      </div>
      <div class="detail-row">
        <span class="label">Status</span>
        <span class="value"><?= esc($statuses[$message['status']] ?? $message['status']) ?></span>
      </div>

      <div class="mt-3">
        <div class="label small text-muted mb-2" style="text-transform:uppercase; letter-spacing:0.4px; font-weight:600;">Message</div>
        <div style="white-space: pre-wrap; background: var(--light-bg); border-radius: var(--radius-sm); padding: 16px; font-size: 0.92rem; line-height: 1.6;">
<?= esc($message['message'] ?? '') ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="panel-card">
      <h2 class="mb-3">Update Status</h2>
      <form method="post" action="<?= site_url('contacts/' . $message['id'] . '/status') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label small">Status</label>
          <select name="status" class="form-select">
            <?php foreach ($statuses as $key => $label) : ?>
              <option value="<?= esc($key) ?>" <?= $message['status'] === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-gold w-100">Update Status</button>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
