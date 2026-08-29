<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="filter-bar">
  <form method="get" action="<?= site_url('contacts') ?>" class="row g-2 align-items-end">
    <div class="col-md-3">
      <label class="form-label small mb-1">Search</label>
      <input type="text" name="q" class="form-control form-control-sm" placeholder="Name, mobile or email" value="<?= esc($filterQ) ?>">
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">Status</label>
      <select name="status" class="form-select form-select-sm">
        <option value="">All Statuses</option>
        <?php foreach ($statuses as $key => $label) : ?>
          <option value="<?= esc($key) ?>" <?= $filterStatus === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">From</label>
      <input type="date" name="date_from" class="form-control form-control-sm" value="<?= esc($filterDateFrom) ?>">
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">To</label>
      <input type="date" name="date_to" class="form-control form-control-sm" value="<?= esc($filterDateTo) ?>">
    </div>
    <div class="col-md-3 d-flex gap-2">
      <button type="submit" class="btn btn-navy btn-sm"><i class="bi bi-funnel"></i> Filter</button>
      <a href="<?= site_url('contacts') ?>" class="btn btn-outline-navy btn-sm">Reset</a>
    </div>
  </form>
</div>

<div class="panel-card">
  <?php if (empty($messages)) : ?>
    <p class="text-muted mb-0">
      <?= $filtersApplied ? 'No contact messages match your filters.' : 'No contact messages yet.' ?>
    </p>
  <?php else : ?>
    <?php
      $perPage   = $pager->getPerPage();
      $total     = $pager->getTotal();
      $firstItem = ($pager->getCurrentPage() - 1) * $perPage + 1;
      $lastItem  = min($firstItem + $perPage - 1, $total);
    ?>
    <p class="text-muted small mb-3">
      Showing <?= (int) $firstItem ?>&ndash;<?= (int) $lastItem ?> of <?= (int) $total ?> messages
    </p>
    <div class="table-responsive">
      <table class="table table-clean mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Message</th>
            <th>Status</th>
            <th>Received</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $msg) :
            $preview = mb_substr((string) $msg['message'], 0, 60);
            $preview = esc($preview) . (mb_strlen((string) $msg['message']) > 60 ? '&hellip;' : '');
        ?>
          <tr onclick="window.location='<?= site_url('contacts/' . $msg['id']) ?>'" style="cursor:pointer;">
            <td><?= esc($msg['full_name']) ?></td>
            <td><?= esc($msg['mobile']) ?></td>
            <td><?= esc($msg['email']) ?></td>
            <td class="text-muted"><?= $preview ?></td>
            <td><span class="badge-status <?= $statusBadge[$msg['status']] ?? '' ?>"><?= esc($statuses[$msg['status']] ?? $msg['status']) ?></span></td>
            <td class="text-muted small"><?= date('d M Y, h:i A', strtotime($msg['created_at'])) ?></td>
            <td>
              <a href="<?= site_url('contacts/' . $msg['id']) ?>" class="btn btn-outline-navy btn-sm" onclick="event.stopPropagation();">View</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="pager-clean mt-3">
      <?= $pager->links() ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>
