<?php

namespace App\Models;

use CodeIgniter\Model;

class LeadStatusHistoryModel extends Model
{
    protected $table            = 'lead_status_history';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['lead_id', 'old_status', 'new_status', 'note', 'changed_by'];

    protected $useTimestamps = false; // has only created_at, set manually via default

    protected $beforeInsert = ['setCreatedAt'];

    protected function setCreatedAt(array $data): array
    {
        $data['data']['created_at'] = $data['data']['created_at'] ?? date('Y-m-d H:i:s');
        return $data;
    }

    public function forLead(int $leadId): array
    {
        return $this->where('lead_id', $leadId)->orderBy('created_at', 'DESC')->findAll();
    }
}
