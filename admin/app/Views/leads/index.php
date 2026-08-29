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

<div class="filter-bar">
  <form method="get" action="<?= site_url('leads') ?>" class="row g-2 align-items-end">
    <div class="col-md-3">
      <label class="form-label small mb-1">Search</label>
      <input type="text" class="form-control form-control-sm" name="q" value="<?= esc($filters['q']) ?>" placeholder="Name, mobile or email">
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">Status</label>
      <select class="form-select form-select-sm" name="status">
        <option value="">All Statuses</option>
        <?php foreach ($statuses as $key => $label) : ?>
          <option value="<?= esc($key) ?>" <?= $filters['status'] === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">Loan Type</label>
      <select class="form-select form-select-sm" name="loan_type">
        <option value="">All Types</option>
        <?php foreach ($loanTypes as $type) : ?>
          <option value="<?= esc($type) ?>" <?= $filters['loan_type'] === $type ? 'selected' : '' ?>><?= esc($type) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">From</label>
      <input type="date" class="form-control form-control-sm" name="date_from" value="<?= esc($filters['date_from']) ?>">
    </div>
    <div class="col-md-2">
      <label class="form-label small mb-1">To</label>
      <input type="date" class="form-control form-control-sm" name="date_to" value="<?= esc($filters['date_to']) ?>">
    </div>
    <div class="col-md-1 d-flex gap-1">
      <button type="submit" class="btn btn-navy btn-sm flex-fill">Filter</button>
    </div>
    <?php if ($hasFilters) : ?>
      <div class="col-12">
        <a href="<?= site_url('leads') ?>" class="small text-muted">Reset filters</a>
      </div>
    <?php endif; ?>
  </form>
</div>

<div class="panel-card">
  <?php if (empty($leads)) : ?>
    <p class="text-muted mb-0"><?= $hasFilters ? 'No leads match your filters.' : 'No leads yet.' ?></p>
  <?php else : ?>
    <?php
      $start = $totalLeads === 0 ? 0 : (($currentPage - 1) * $perPage) + 1;
      $end   = min($currentPage * $perPage, $totalLeads);
    ?>
    <p class="text-muted small mb-3">Showing <?= (int) $start ?>&ndash;<?= (int) $end ?> of <?= (int) $totalLeads ?> leads</p>
    <div class="table-responsive">
      <table class="table table-clean mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <th>Loan Type</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Received</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($leads as $lead) : ?>
          <tr onclick="window.location='<?= site_url('leads/' . $lead['id']) ?>'" style="cursor:pointer;">
            <td><?= esc($lead['full_name']) ?></td>
            <td><?= esc($lead['mobile']) ?></td>
            <td><?= esc($lead['email']) ?></td>
            <td><?= esc($lead['loan_type']) ?></td>
            <td><?= finzo_format_inr($lead['loan_amount']) ?></td>
            <td><span class="badge-status <?= \App\Models\LeadModel::STATUS_BADGE_CLASS[$lead['status']] ?? '' ?>"><?= esc($statuses[$lead['status']] ?? $lead['status']) ?></span></td>
            <td class="text-muted small"><?= date('d M Y, h:i A', strtotime($lead['created_at'])) ?></td>
            <td onclick="event.stopPropagation();">
              <a href="<?= site_url('leads/' . $lead['id']) ?>" class="btn btn-outline-navy btn-sm">View</a>
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
