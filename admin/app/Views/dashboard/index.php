<?= $this->extend('layout/admin') ?>
<?= $this->section('content') ?>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(212,175,55,0.14); color:#a3801f;"><i class="bi bi-people"></i></span>
      <div class="stat-value"><?= (int) $totalLeads ?></div>
      <div class="stat-label">Total Leads (Apply Now)</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(13,110,253,0.1); color:#0d6efd;"><i class="bi bi-stars"></i></span>
      <div class="stat-value"><?= (int) $newLeads ?></div>
      <div class="stat-label">New / Unactioned Leads</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(25,135,84,0.12); color:#198754;"><i class="bi bi-check2-circle"></i></span>
      <div class="stat-value"><?= (int) $approvedLeads ?></div>
      <div class="stat-label">Approved Leads</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(212,175,55,0.14); color:#a3801f;"><i class="bi bi-envelope-paper"></i></span>
      <div class="stat-value"><?= (int) $totalContacts ?></div>
      <div class="stat-label">Contact Messages</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-8">
    <div class="chart-card h-100">
      <h5>Leads Received — Last 14 Days</h5>
      <p class="text-muted small mb-3">Daily count of Apply Now submissions.</p>
      <canvas id="leadsTrendChart" height="90"></canvas>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="chart-card h-100">
      <h5>Leads by Status</h5>
      <p class="text-muted small mb-3">Current pipeline breakdown.</p>
      <canvas id="leadStatusChart" height="220"></canvas>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-6">
    <div class="chart-card h-100">
      <h5>Leads by Loan Type</h5>
      <p class="text-muted small mb-3">Which services are generating the most interest.</p>
      <canvas id="loanTypeChart" height="200"></canvas>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="chart-card h-100">
      <h5>Contact Messages by Status</h5>
      <p class="text-muted small mb-3">Enquiries from the Contact page.</p>
      <canvas id="contactStatusChart" height="200"></canvas>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="panel-card h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Recent Leads</h2>
        <a href="<?= site_url('leads') ?>" class="btn btn-outline-navy btn-sm">View All</a>
      </div>
      <?php if (empty($recentLeads)) : ?>
        <p class="text-muted mb-0">No leads yet.</p>
      <?php else : ?>
        <div class="table-responsive">
          <table class="table table-clean mb-0">
            <thead><tr><th>Name</th><th>Loan Type</th><th>Status</th><th>Received</th></tr></thead>
            <tbody>
            <?php foreach ($recentLeads as $lead) : ?>
              <tr onclick="window.location='<?= site_url('leads/' . $lead['id']) ?>'" style="cursor:pointer;">
                <td><?= esc($lead['full_name']) ?></td>
                <td><?= esc($lead['loan_type']) ?></td>
                <td><span class="badge-status <?= \App\Models\LeadModel::STATUS_BADGE_CLASS[$lead['status']] ?? '' ?>"><?= esc($leadStatusLabels[$lead['status']] ?? $lead['status']) ?></span></td>
                <td class="text-muted small"><?= date('d M, h:i A', strtotime($lead['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="panel-card h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Recent Contact Messages</h2>
        <a href="<?= site_url('contacts') ?>" class="btn btn-outline-navy btn-sm">View All</a>
      </div>
      <?php if (empty($recentContacts)) : ?>
        <p class="text-muted mb-0">No messages yet.</p>
      <?php else : ?>
        <div class="table-responsive">
          <table class="table table-clean mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Received</th></tr></thead>
            <tbody>
            <?php foreach ($recentContacts as $msg) : ?>
              <tr onclick="window.location='<?= site_url('contacts/' . $msg['id']) ?>'" style="cursor:pointer;">
                <td><?= esc($msg['full_name']) ?></td>
                <td><?= esc($msg['email']) ?></td>
                <td><span class="badge-status <?= \App\Models\ContactMessageModel::STATUS_BADGE_CLASS[$msg['status']] ?? '' ?>"><?= esc($contactStatusLabels[$msg['status']] ?? $msg['status']) ?></span></td>
                <td class="text-muted small"><?= date('d M, h:i A', strtotime($msg['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
  var navy = '#071B49', gold = '#D4AF37', lightGold = '#F1D27A', muted = '#8a95ad';
  Chart.defaults.font.family = "'Inter', 'Segoe UI', Arial, sans-serif";
  Chart.defaults.color = muted;

  // Leads trend
  new Chart(document.getElementById('leadsTrendChart'), {
    type: 'line',
    data: {
      labels: <?= json_encode(array_map(fn($r) => date('d M', strtotime($r['day'])), $leadsTrend)) ?>,
      datasets: [{
        label: 'Leads',
        data: <?= json_encode(array_map(fn($r) => $r['total'], $leadsTrend)) ?>,
        borderColor: gold,
        backgroundColor: 'rgba(212,175,55,0.12)',
        fill: true,
        tension: 0.35,
        pointRadius: 3,
        pointBackgroundColor: navy,
      }]
    },
    options: {
      plugins: { legend: { display: false } },
      scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });

  // Lead status doughnut
  new Chart(document.getElementById('leadStatusChart'), {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_values($leadStatusLabels)) ?>,
      datasets: [{
        data: <?= json_encode(array_values($leadStatusCounts)) ?>,
        backgroundColor: [navy, '#0d6efd', '#ffc107', '#198754', '#dc3545', '#6c757d'],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } } }
  });

  // Loan type bar
  new Chart(document.getElementById('loanTypeChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_map(fn($r) => $r['loan_type'], $leadsByLoanType)) ?>,
      datasets: [{
        label: 'Leads',
        data: <?= json_encode(array_map(fn($r) => (int) $r['total'], $leadsByLoanType)) ?>,
        backgroundColor: gold,
        borderRadius: 6,
        maxBarThickness: 34
      }]
    },
    options: {
      indexAxis: 'y',
      plugins: { legend: { display: false } },
      scales: { x: { beginAtZero: true, ticks: { precision: 0 } } }
    }
  });

  // Contact status doughnut
  new Chart(document.getElementById('contactStatusChart'), {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_values($contactStatusLabels)) ?>,
      datasets: [{
        data: <?= json_encode(array_values($contactStatusCounts)) ?>,
        backgroundColor: [navy, '#0d6efd', '#198754', '#6c757d'],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } } }
  });
})();
</script>
<?= $this->endSection() ?>
