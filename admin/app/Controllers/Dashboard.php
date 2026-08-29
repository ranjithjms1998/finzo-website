<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;
use App\Models\LeadModel;
use CodeIgniter\Controller;

class Dashboard extends Controller
{
    public function index()
    {
        $leadModel    = new LeadModel();
        $contactModel = new ContactMessageModel();

        $leadStatusCounts    = $leadModel->countByStatus();
        $contactStatusCounts = $contactModel->countByStatus();
        $leadsByLoanType     = $leadModel->countByLoanType();
        $leadsTrend          = $leadModel->countsByDay(14);

        $totalLeads      = array_sum($leadStatusCounts);
        $totalContacts   = array_sum($contactStatusCounts);
        $newLeads        = $leadStatusCounts['new'] ?? 0;
        $approvedLeads   = $leadStatusCounts['approved'] ?? 0;

        $data = [
            'title'               => 'Dashboard',
            'totalLeads'          => $totalLeads,
            'totalContacts'       => $totalContacts,
            'newLeads'            => $newLeads,
            'approvedLeads'       => $approvedLeads,
            'leadStatusCounts'    => $leadStatusCounts,
            'contactStatusCounts' => $contactStatusCounts,
            'leadsByLoanType'     => $leadsByLoanType,
            'leadsTrend'          => $leadsTrend,
            'recentLeads'         => $leadModel->recent(6),
            'recentContacts'      => $contactModel->recent(6),
            'leadStatusLabels'    => LeadModel::STATUSES,
            'contactStatusLabels' => ContactMessageModel::STATUSES,
        ];

        return view('dashboard/index', $data);
    }
}
