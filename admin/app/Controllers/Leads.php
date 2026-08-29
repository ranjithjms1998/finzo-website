<?php

namespace App\Controllers;

use App\Models\AdminUserModel;
use App\Models\LeadModel;
use App\Models\LeadStatusHistoryModel;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;

class Leads extends Controller
{
    public function index()
    {
        $leadModel = new LeadModel();

        $status    = trim((string) $this->request->getGet('status'));
        $loanType  = trim((string) $this->request->getGet('loan_type'));
        $q         = trim((string) $this->request->getGet('q'));
        $dateFrom  = trim((string) $this->request->getGet('date_from'));
        $dateTo    = trim((string) $this->request->getGet('date_to'));

        $builder = $leadModel->orderBy('created_at', 'DESC');

        if ($status !== '' && array_key_exists($status, LeadModel::STATUSES)) {
            $builder->where('status', $status);
        }

        if ($loanType !== '') {
            $builder->where('loan_type', $loanType);
        }

        if ($q !== '') {
            $builder->groupStart()
                ->like('full_name', $q)
                ->orLike('mobile', $q)
                ->orLike('email', $q)
                ->groupEnd();
        }

        if ($dateFrom !== '') {
            $builder->where('created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo !== '') {
            $builder->where('created_at <=', $dateTo . ' 23:59:59');
        }

        $leads = $builder->paginate(20, 'default');
        $pager = $leadModel->pager;

        // Preserve current filter query string across pagination links.
        $filters = array_filter([
            'status'    => $status,
            'loan_type' => $loanType,
            'q'         => $q,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ], static fn ($v) => $v !== '');
        $pager->setPath('leads');
        if ($filters !== []) {
            $pager->only(array_keys($filters));
        }

        $loanTypes = array_column(
            $leadModel->distinct()->select('loan_type')->orderBy('loan_type', 'ASC')->findAll(),
            'loan_type'
        );

        $data = [
            'title'         => 'Leads',
            'leads'         => $leads,
            'pager'         => $pager,
            'statuses'      => LeadModel::STATUSES,
            'loanTypes'     => $loanTypes,
            'filters'       => [
                'status'    => $status,
                'loan_type' => $loanType,
                'q'         => $q,
                'date_from' => $dateFrom,
                'date_to'   => $dateTo,
            ],
            'hasFilters'    => $filters !== [],
            'totalLeads'    => $pager->getTotal('default'),
            'currentPage'   => $pager->getCurrentPage('default'),
            'perPage'       => $pager->getPerPage('default'),
        ];

        return view('leads/index', $data);
    }

    public function show($id = null)
    {
        $leadModel = new LeadModel();
        $lead = $leadModel->find((int) $id);

        if (! $lead) {
            throw PageNotFoundException::forPageNotFound();
        }

        $historyModel = new LeadStatusHistoryModel();
        $adminModel   = new AdminUserModel();

        $history = $historyModel->forLead((int) $id);

        // Attach the admin's full name (or "System") for each history row.
        $adminNames = [];
        foreach ($history as $row) {
            if ($row['changed_by'] !== null && ! isset($adminNames[$row['changed_by']])) {
                $admin = $adminModel->find($row['changed_by']);
                $adminNames[$row['changed_by']] = $admin['full_name'] ?? 'Unknown';
            }
        }

        $data = [
            'title'      => 'Lead: ' . $lead['full_name'],
            'lead'       => $lead,
            'statuses'   => LeadModel::STATUSES,
            'history'    => $history,
            'adminNames' => $adminNames,
        ];

        return view('leads/show', $data);
    }

    public function updateStatus($id = null)
    {
        $leadModel = new LeadModel();
        $lead = $leadModel->find((int) $id);

        if (! $lead) {
            throw PageNotFoundException::forPageNotFound();
        }

        $newStatus = (string) $this->request->getPost('status');
        $note      = trim((string) $this->request->getPost('note'));

        if (! array_key_exists($newStatus, LeadModel::STATUSES)) {
            session()->setFlashdata('error', 'Invalid status selected.');
            return redirect()->to(site_url('leads/' . $id));
        }

        $oldStatus = $lead['status'];

        // Nothing changed and no note added — skip the no-op update/history entry.
        if ($newStatus === $oldStatus && $note === '') {
            session()->setFlashdata('success', 'No changes to save.');
            return redirect()->to(site_url('leads/' . $id));
        }

        $leadModel->update($id, ['status' => $newStatus]);

        $historyModel = new LeadStatusHistoryModel();
        $historyModel->insert([
            'lead_id'    => (int) $id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'note'       => $note !== '' ? $note : null,
            'changed_by' => session()->get('admin_id'),
        ]);

        session()->setFlashdata('success', 'Lead status updated successfully.');
        return redirect()->to(site_url('leads/' . $id));
    }
}
