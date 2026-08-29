<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'Dashboard') ?> | Finzo Admin</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
<?= $this->renderSection('head') ?>
</head>
<body>

<div class="admin-shell">
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="brand">
      <span class="emblem">S</span>
      <span>
        <strong>FINZO</strong>
        <span>Admin Panel</span>
      </span>
    </div>
    <nav class="admin-nav">
      <?php $current = uri_string(); ?>
      <a href="<?= site_url('dashboard') ?>" class="nav-link <?= str_starts_with($current, 'dashboard') || $current === '' ? 'active' : '' ?>">
        <i class="bi bi-grid-1x2"></i> Dashboard
      </a>
      <a href="<?= site_url('leads') ?>" class="nav-link <?= str_starts_with($current, 'leads') ? 'active' : '' ?>">
        <i class="bi bi-person-lines-fill"></i> Leads
      </a>
      <a href="<?= site_url('contacts') ?>" class="nav-link <?= str_starts_with($current, 'contacts') ? 'active' : '' ?>">
        <i class="bi bi-chat-square-text"></i> Contact Messages
      </a>
      <a href="<?= site_url('reports') ?>" class="nav-link <?= str_starts_with($current, 'reports') ? 'active' : '' ?>">
        <i class="bi bi-bar-chart-line"></i> Reports
      </a>
      <a href="<?= site_url('account') ?>" class="nav-link <?= str_starts_with($current, 'account') ? 'active' : '' ?>">
        <i class="bi bi-gear"></i> Account Settings
      </a>
    </nav>
    <div class="admin-sidebar-footer">
      &copy; <?= date('Y') ?> Finzo Financial Services
    </div>
  </aside>

  <div class="admin-main">
    <header class="admin-topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle menu"><i class="bi bi-list"></i></button>
        <h1><?= esc($title ?? 'Dashboard') ?></h1>
      </div>
      <div class="d-flex align-items-center gap-3">
        <div class="user-chip">
          <span class="avatar"><?= esc(strtoupper(substr(session()->get('admin_full_name') ?? 'A', 0, 1))) ?></span>
          <span><?= esc(session()->get('admin_full_name')) ?></span>
        </div>
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-navy btn-sm"><i class="bi bi-box-arrow-right"></i> Logout</a>
      </div>
    </header>

    <main class="admin-content">
      <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i><?= esc(session()->getFlashdata('success')) ?></div>
      <?php endif; ?>
      <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger d-flex align-items-center gap-2"><i class="bi bi-exclamation-triangle-fill"></i><?= esc(session()->getFlashdata('error')) ?></div>
      <?php endif; ?>

      <?= $this->renderSection('content') ?>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('adminSidebar').classList.toggle('show');
  });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>
