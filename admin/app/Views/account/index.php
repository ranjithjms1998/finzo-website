<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="panel-card">
      <h2 class="mb-1">Change Password</h2>
      <p class="text-muted small mb-4">Logged in as <strong><?= esc(session()->get('admin_username')) ?></strong> (<?= esc(session()->get('admin_full_name')) ?>)</p>

      <?php $errors = session()->getFlashdata('errors'); ?>
      <?php if ($errors) : ?>
        <div class="alert alert-danger py-2 small">
          <?php foreach ($errors as $err) : ?><div><?= esc($err) ?></div><?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form method="post" action="<?= site_url('account/password') ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label" for="current_password">Current Password</label>
          <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="new_password">New Password</label>
          <input type="password" class="form-control" id="new_password" name="new_password" minlength="8" required>
          <div class="form-text">At least 8 characters.</div>
        </div>
        <div class="mb-4">
          <label class="form-label" for="confirm_password">Confirm New Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" minlength="8" required>
        </div>
        <button type="submit" class="btn btn-gold">Update Password</button>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
