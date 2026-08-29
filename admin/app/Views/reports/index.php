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

$exportQuery = http_build_query(['date_from' => $dateFrom, 'date_to' => $dateTo]);
?>

<div class="filter-bar">
  <form method="get" action="<?= site_url('reports') ?>" class="row g-2 align-items-end">
    <div class="col-md-3">
      <label class="form-label small mb-1">From</label>
      <input type="date" class="form-control form-control-sm" name="date_from" value="<?= esc($dateFrom) ?>">
    </div>
    <div class="col-md-3">
      <label class="form-label small mb-1">To</label>
      <input type="date" class="form-control form-control-sm" name="date_to" value="<?= esc($dateTo) ?>">
    </div>
    <div class="col-md-2 d-flex gap-1">
      <button type="submit" class="btn btn-navy btn-sm flex-fill">Apply</button>
    </div>
    <div class="col-12">
      <a href="<?= site_url('reports') ?>" class="small text-muted">Reset to last 30 days</a>
    </div>
  </form>
</div>

<div class="row g-3 mb-4">
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(212,175,55,0.14); color:#a3801f;"><i class="bi bi-people"></i></span>
      <div class="stat-value"><?= (int) $totalLeads ?></div>
      <div class="stat-label">Total Leads in Range</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(212,175,55,0.14); color:#a3801f;"><i class="bi bi-envelope-paper"></i></span>
      <div class="stat-value"><?= (int) $totalContacts ?></div>
      <div class="stat-label">Contact Messages in Range</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(25,135,84,0.12); color:#198754;"><i class="bi bi-check2-circle"></i></span>
      <div class="stat-value"><?= (int) $approvedLeads ?> <span class="text-muted" style="font-size:0.95rem;">(<?= esc((string) $approvalRate) ?>%)</span></div>
      <div class="stat-label">Approved Leads / Approval Rate</div>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="stat-card">
      <span class="stat-icon" style="background:rgba(13,110,253,0.1); color:#0d6efd;"><i class="bi bi-cash-stack"></i></span>
      <div class="stat-value"><?= esc(finzo_format_inr($avgLoanAmount)) ?></div>
      <div class="stat-label">Avg. Loan Amount Requested</div>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-12">
    <div class="chart-card h-100">
      <h5>Leads Received — <?= esc(date('d M Y', strtotime($dateFrom))) ?> to <?= esc(date('d M Y', strtotime($dateTo))) ?></h5>
      <p class="text-muted small mb-3">
        <?= $trendGranularity === 'month' ? 'Monthly totals for the selected range.' : 'Daily totals for the selected range.' ?>
      </p>
      <canvas id="leadsTrendChart" height="80"></canvas>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-4">
    <div class="chart-card h-100">
      <h5>Leads by Status</h5>
      <p class="text-muted small mb-3">Pipeline breakdown within the selected range.</p>
      <canvas id="leadStatusChart" height="220"></canvas>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="chart-card h-100">
      <h5>Leads by Loan Type</h5>
      <p class="text-muted small mb-3">Which services generated the most interest.</p>
      <canvas id="loanTypeChart" height="220"></canvas>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="chart-card h-100">
      <h5>Leads by Employment Type</h5>
      <p class="text-muted small mb-3">Applicant profile within the selected range.</p>
      <canvas id="employmentTypeChart" height="220"></canvas>
    </div>
  </div>
</div>

<div class="row g-3 mb-4">
  <div class="col-lg-6">
    <div class="chart-card h-100">
      <h5>Contact Messages by Status</h5>
      <p class="text-muted small mb-3">Enquiries from the Contact page within the selected range.</p>
      <canvas id="contactStatusChart" height="200"></canvas>
    </div>
  </div>
</div>

<div class="row g-3">
  <div class="col-lg-6">
    <div class="panel-card h-100">
      <h2>Export Leads</h2>
      <p class="text-muted small">
        Download every lead submitted between <?= esc(date('d M Y', strtotime($dateFrom))) ?> and
        <?= esc(date('d M Y', strtotime($dateTo))) ?> as a CSV file, including contact details,
        loan details and current status.
      </p>
      <a class="btn btn-navy" href="<?= site_url('reports/export/leads') ?>?<?= $exportQuery ?>">
        <i class="bi bi-download"></i> Download CSV
      </a>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="panel-card h-100">
      <h2>Export Contact Messages</h2>
      <p class="text-muted small">
        Download every contact message received between <?= esc(date('d M Y', strtotime($dateFrom))) ?> and
        <?= esc(date('d M Y', strtotime($dateTo))) ?> as a CSV file.
      </p>
      <a class="btn btn-navy" href="<?= site_url('reports/export/contacts') ?>?<?= $exportQuery ?>">
        <i class="bi bi-download"></i> Download CSV
      </a>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
(function () {
  var navy = '#071B49', gold = '#D4AF37', muted = '#8a95ad';
  Chart.defaults.font.family = "'Inter', 'Segoe UI', Arial, sans-serif";
  Chart.defaults.color = muted;

  // Leads trend
  new Chart(document.getElementById('leadsTrendChart'), {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_map(function ($r) use ($trendGranularity) {
          return $trendGranularity === 'month' ? date('M Y', strtotime($r['bucket'] . '-01')) : date('d M', strtotime($r['bucket']));
      }, $leadsTrend)) ?>,
      datasets: [{
        label: 'Leads',
        data: <?= json_encode(array_map(fn($r) => $r['total'], $leadsTrend)) ?>,
        backgroundColor: gold,
        borderRadius: 6,
        maxBarThickness: 40
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

  // Employment type doughnut
  new Chart(document.getElementById('employmentTypeChart'), {
    type: 'doughnut',
    data: {
      labels: <?= json_encode(array_map(fn($r) => $r['employment_type'], $leadsByEmploymentType)) ?>,
      datasets: [{
        data: <?= json_encode(array_map(fn($r) => (int) $r['total'], $leadsByEmploymentType)) ?>,
        backgroundColor: [navy, gold, '#0d6efd', '#198754', '#dc3545', '#6c757d', '#ffc107'],
        borderWidth: 2,
        borderColor: '#fff'
      }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, padding: 12, font: { size: 11 } } } } }
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
