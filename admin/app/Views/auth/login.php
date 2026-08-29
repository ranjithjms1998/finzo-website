<?= $this->extend('layout/auth') ?>
<?= $this->section('content') ?>

<div class="auth-shell">
  <div class="auth-card">
    <div class="auth-logo">
      <span class="emblem">S</span>
      <span class="brand">
        <strong>FINZO</strong>
        <span>Admin Panel</span>
      </span>
    </div>
    <h1>Welcome Back</h1>
    <p class="sub">Sign in to manage leads, enquiries and reports.</p>

    <?php if (session()->getFlashdata('error')) : ?>
      <div class="alert alert-danger py-2 small"><?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if (isset($validation) && $validation->getErrors()) : ?>
      <div class="alert alert-danger py-2 small">
        <?php foreach ($validation->getErrors() as $err) : ?>
          <div><?= esc($err) ?></div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('login') ?>" novalidate>
      <?= csrf_field() ?>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" value="<?= esc(old('username')) ?>" required autofocus>
      </div>
      <div class="mb-4">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <button type="submit" class="btn btn-gold w-100">Sign In</button>
    </form>
  </div>
</div>

<?= $this->endSection() ?>
