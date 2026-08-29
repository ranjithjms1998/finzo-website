<?php

namespace App\Models;

use CodeIgniter\Model;

class LeadModel extends Model
{
    protected $table            = 'leads';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'full_name', 'mobile', 'email', 'loan_type', 'loan_amount', 'employment_type',
        'city', 'message', 'status', 'source', 'assigned_to',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    public const STATUSES = [
        'new'         => 'New',
        'contacted'   => 'Contacted',
        'in_progress' => 'In Progress',
        'approved'    => 'Approved',
        'rejected'    => 'Rejected',
        'closed'      => 'Closed',
    ];

    public const STATUS_BADGE_CLASS = [
        'new'         => 'badge-status-new',
        'contacted'   => 'badge-status-info',
        'in_progress' => 'badge-status-warn',
        'approved'    => 'badge-status-success',
        'rejected'    => 'badge-status-danger',
        'closed'      => 'badge-status-muted',
    ];

    public function countByStatus(): array
    {
        $rows = $this->select('status, COUNT(*) as total')->groupBy('status')->findAll();
        $counts = array_fill_keys(array_keys(self::STATUSES), 0);
        foreach ($rows as $row) {
            $counts[$row['status']] = (int) $row['total'];
        }
        return $counts;
    }

    public function countByLoanType(): array
    {
        return $this->select('loan_type, COUNT(*) as total')
            ->groupBy('loan_type')
            ->orderBy('total', 'DESC')
            ->findAll();
    }

    /** Leads created per day for the last $days days (for the trend chart). */
    public function countsByDay(int $days = 14): array
    {
        $db = $this->db;
        $rows = $db->table('leads')
            ->select('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at >=', date('Y-m-d 00:00:00', strtotime("-" . ($days - 1) . " days")))
            ->groupBy('DATE(created_at)')
            ->orderBy('day', 'ASC')
            ->get()
            ->getResultArray();

        $byDay = [];
        foreach ($rows as $row) {
            $byDay[$row['day']] = (int) $row['total'];
        }

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $result[] = ['day' => $date, 'total' => $byDay[$date] ?? 0];
        }
        return $result;
    }

    public function recent(int $limit = 5): array
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }

    /**
     * Apply an optional [$dateFrom, $dateTo] (both 'Y-m-d' or null) bound to created_at
     * on the model's current query builder. Null bounds are simply skipped, so passing
     * both as null leaves the query unfiltered (used by CSV export's "everything" mode).
     */
    private function scopeDateRange(?string $dateFrom, ?string $dateTo): self
    {
        if (! empty($dateFrom)) {
            $this->where('created_at >=', $dateFrom . ' 00:00:00');
        }
        if (! empty($dateTo)) {
            $this->where('created_at <=', $dateTo . ' 23:59:59');
        }
        return $this;
    }

    /** Total leads within an optional date range (for Reports stat cards). */
    public function totalInRange(?string $dateFrom = null, ?string $dateTo = null): int
    {
        return $this->scopeDateRange($dateFrom, $dateTo)->countAllResults();
    }

    /** Leads by status within an optional date range, same shape as countByStatus(). */
    public function countByStatusInRange(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $rows = $this->select('status, COUNT(*) as total')
            ->scopeDateRange($dateFrom, $dateTo)
            ->groupBy('status')
            ->findAll();

        $counts = array_fill_keys(array_keys(self::STATUSES), 0);
        foreach ($rows as $row) {
            $counts[$row['status']] = (int) $row['total'];
        }
        return $counts;
    }

    /** Leads by loan type within an optional date range, same shape as countByLoanType(). */
    public function countByLoanTypeInRange(?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->select('loan_type, COUNT(*) as total')
            ->scopeDateRange($dateFrom, $dateTo)
            ->groupBy('loan_type')
            ->orderBy('total', 'DESC')
            ->findAll();
    }

    /** Leads by employment type within an optional date range. */
    public function countByEmploymentTypeInRange(?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->select('employment_type, COUNT(*) as total')
            ->scopeDateRange($dateFrom, $dateTo)
            ->groupBy('employment_type')
            ->orderBy('total', 'DESC')
            ->findAll();
    }

    /** Average requested loan amount within an optional date range. */
    public function averageLoanAmountInRange(?string $dateFrom = null, ?string $dateTo = null): float
    {
        $row = $this->select('AVG(loan_amount) as avg_amount')
            ->scopeDateRange($dateFrom, $dateTo)
            ->get()
            ->getRowArray();

        return (float) ($row['avg_amount'] ?? 0);
    }

    /**
     * Leads created within [$dateFrom, $dateTo] (both required 'Y-m-d'), bucketed by day
     * or month. The Reports page uses 'day' buckets for ranges of 31 days or less (each
     * point stays meaningful) and 'month' buckets for longer ranges (keeps the chart
     * readable instead of plotting hundreds of daily points).
     */
    public function trendInRange(string $dateFrom, string $dateTo, string $granularity = 'day'): array
    {
        $format = $granularity === 'month' ? '%Y-%m' : '%Y-%m-%d';

        $rows = $this->db->table('leads')
            ->select("DATE_FORMAT(created_at, '{$format}') as bucket, COUNT(*) as total")
            ->where('created_at >=', $dateFrom . ' 00:00:00')
            ->where('created_at <=', $dateTo . ' 23:59:59')
            ->groupBy('bucket')
            ->orderBy('bucket', 'ASC')
            ->get()
            ->getResultArray();

        $byBucket = [];
        foreach ($rows as $row) {
            $byBucket[$row['bucket']] = (int) $row['total'];
        }

        $result = [];
        if ($granularity === 'month') {
            $cursor = new \DateTime(date('Y-m-01', strtotime($dateFrom)));
            $end    = new \DateTime(date('Y-m-01', strtotime($dateTo)));
            while ($cursor <= $end) {
                $key      = $cursor->format('Y-m');
                $result[] = ['bucket' => $key, 'total' => $byBucket[$key] ?? 0];
                $cursor->modify('+1 month');
            }
        } else {
            $cursor = new \DateTime($dateFrom);
            $end    = new \DateTime($dateTo);
            while ($cursor <= $end) {
                $key      = $cursor->format('Y-m-d');
                $result[] = ['bucket' => $key, 'total' => $byBucket[$key] ?? 0];
                $cursor->modify('+1 day');
            }
        }
        return $result;
    }

    /** Raw rows for CSV export within an optional date range (null/null = everything). */
    public function forExport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->scopeDateRange($dateFrom, $dateTo)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }
}
