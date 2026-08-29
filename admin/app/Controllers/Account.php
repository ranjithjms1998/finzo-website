<?php

namespace App\Controllers;

use App\Models\AdminUserModel;
use CodeIgniter\Controller;

class Account extends Controller
{
    public function index()
    {
        return view('account/index', ['title' => 'Account Settings']);
    }

    public function updatePassword()
    {
        $rules = [
            'current_password' => 'required',
            'new_password'      => 'required|min_length[8]',
            'confirm_password'  => 'required|matches[new_password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('account'))->with('errors', $this->validator->getErrors());
        }

        $userModel = new AdminUserModel();
        $user = $userModel->find(session()->get('admin_id'));

        if (! $user || ! password_verify($this->request->getPost('current_password'), $user['password_hash'])) {
            return redirect()->to(site_url('account'))->with('error', 'Your current password is incorrect.');
        }

        $userModel->update($user['id'], [
            'password_hash' => password_hash($this->request->getPost('new_password'), PASSWORD_BCRYPT),
        ]);

        return redirect()->to(site_url('account'))->with('success', 'Password updated successfully.');
    }
}
