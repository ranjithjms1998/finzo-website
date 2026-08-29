<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactMessageModel extends Model
{
    protected $table            = 'contact_messages';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['full_name', 'mobile', 'email', 'message', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    public const STATUSES = [
        'new'       => 'New',
        'read'      => 'Read',
        'responded' => 'Responded',
        'closed'    => 'Closed',
    ];

    public const STATUS_BADGE_CLASS = [
        'new'       => 'badge-status-new',
        'read'      => 'badge-status-info',
        'responded' => 'badge-status-success',
        'closed'    => 'badge-status-muted',
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

    public function recent(int $limit = 5): array
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }

    /**
     * Apply an optional [$dateFrom, $dateTo] (both 'Y-m-d' or null) bound to created_at
     * on the model's current query builder. Null bounds are skipped, so both-null leaves
     * the query unfiltered (used by CSV export's "everything" mode).
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

    /** Total contact messages within an optional date range (for Reports stat cards). */
    public function totalInRange(?string $dateFrom = null, ?string $dateTo = null): int
    {
        return $this->scopeDateRange($dateFrom, $dateTo)->countAllResults();
    }

    /** Contact messages by status within an optional date range, same shape as countByStatus(). */
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

    /** Raw rows for CSV export within an optional date range (null/null = everything). */
    public function forExport(?string $dateFrom = null, ?string $dateTo = null): array
    {
        return $this->scopeDateRange($dateFrom, $dateTo)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }
}
