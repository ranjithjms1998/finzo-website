<?php

namespace App\Controllers;

use App\Models\AdminUserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function showLogin()
    {
        if (session()->get('admin_id')) {
            return redirect()->to(site_url('dashboard'));
        }
        return view('auth/login', ['title' => 'Login']);
    }

    public function attemptLogin()
    {
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }

        // Basic brute-force throttling by IP: max 8 attempts per 5 minutes.
        $throttler = service('throttler');
        if ($throttler->check(md5($this->request->getIPAddress()), 8, 300) === false) {
            session()->setFlashdata('error', 'Too many login attempts. Please wait a few minutes and try again.');
            return redirect()->to(site_url('login'));
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');

        $userModel = new AdminUserModel();
        $user = $userModel->findByUsername($username);

        if (! $user || ! (int) $user['is_active'] || ! password_verify($password, $user['password_hash'])) {
            session()->setFlashdata('error', 'Invalid username or password.');
            return redirect()->to(site_url('login'))->withInput();
        }

        // Regenerate session ID on privilege change to prevent session fixation.
        session()->regenerate();

        session()->set([
            'admin_id'        => $user['id'],
            'admin_username'  => $user['username'],
            'admin_full_name' => $user['full_name'],
            'admin_role'      => $user['role'],
        ]);

        $userModel->touchLastLogin((int) $user['id']);

        return redirect()->to(site_url('dashboard'));
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to(site_url('login'));
    }
}
