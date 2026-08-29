<?php

namespace App\Controllers;

use App\Models\ContactMessageModel;
use CodeIgniter\Controller;

class Contacts extends Controller
{
    public function index()
    {
        $model = new ContactMessageModel();

        $status   = trim((string) $this->request->getGet('status'));
        $q        = trim((string) $this->request->getGet('q'));
        $dateFrom = trim((string) $this->request->getGet('date_from'));
        $dateTo   = trim((string) $this->request->getGet('date_to'));

        $filtersApplied = false;

        if ($status !== '' && array_key_exists($status, ContactMessageModel::STATUSES)) {
            $model->where('status', $status);
            $filtersApplied = true;
        }

        if ($q !== '') {
            $model->groupStart()
                ->like('full_name', $q)
                ->orLike('mobile', $q)
                ->orLike('email', $q)
                ->groupEnd();
            $filtersApplied = true;
        }

        if ($dateFrom !== '') {
            $model->where('created_at >=', $dateFrom . ' 00:00:00');
            $filtersApplied = true;
        }

        if ($dateTo !== '') {
            $model->where('created_at <=', $dateTo . ' 23:59:59');
            $filtersApplied = true;
        }

        $model->orderBy('created_at', 'DESC');

        $messages = $model->paginate(20);
        $pager    = $model->pager;

        $totalAll = (new ContactMessageModel())->countAll();

        $data = [
            'title'          => 'Contact Messages',
            'messages'       => $messages,
            'pager'          => $pager,
            'statuses'       => ContactMessageModel::STATUSES,
            'statusBadge'    => ContactMessageModel::STATUS_BADGE_CLASS,
            'filterStatus'   => $status,
            'filterQ'        => $q,
            'filterDateFrom' => $dateFrom,
            'filterDateTo'   => $dateTo,
            'filtersApplied' => $filtersApplied,
            'totalAll'       => $totalAll,
        ];

        return view('contacts/index', $data);
    }

    public function show($id = null)
    {
        $model   = new ContactMessageModel();
        $message = $model->find($id);

        if (! $message) {
            return show_404();
        }

        $data = [
            'title'    => 'Message from ' . $message['full_name'],
            'message'  => $message,
            'statuses' => ContactMessageModel::STATUSES,
            'statusBadge' => ContactMessageModel::STATUS_BADGE_CLASS,
        ];

        return view('contacts/show', $data);
    }

    public function updateStatus($id = null)
    {
        $model   = new ContactMessageModel();
        $message = $model->find($id);

        if (! $message) {
            return show_404();
        }

        $status = $this->request->getPost('status');

        if (! array_key_exists($status, ContactMessageModel::STATUSES)) {
            session()->setFlashdata('error', 'Invalid status selected.');
            return redirect()->to(site_url('contacts/' . $id));
        }

        $model->update($id, ['status' => $status]);

        session()->setFlashdata('success', 'Status updated to "' . ContactMessageModel::STATUSES[$status] . '".');

        return redirect()->to(site_url('contacts/' . $id));
    }
}
