<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->get('/', static function () {
    return redirect()->to(session()->get('admin_id') ? site_url('dashboard') : site_url('login'));
});

// --- Public (unauthenticated) routes ---
$routes->get('login', 'Auth::showLogin');
$routes->post('login', 'Auth::attemptLogin');
$routes->get('logout', 'Auth::logout');

// --- Protected admin routes ---
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {
    $routes->get('dashboard', 'Dashboard::index');

    $routes->get('leads', 'Leads::index');
    $routes->get('leads/(:num)', 'Leads::show/$1');
    $routes->post('leads/(:num)/status', 'Leads::updateStatus/$1');

    $routes->get('contacts', 'Contacts::index');
    $routes->get('contacts/(:num)', 'Contacts::show/$1');
    $routes->post('contacts/(:num)/status', 'Contacts::updateStatus/$1');

    $routes->get('reports', 'Reports::index');
    $routes->get('reports/export/leads', 'Reports::exportLeadsCsv');
    $routes->get('reports/export/contacts', 'Reports::exportContactsCsv');

    $routes->get('account', 'Account::index');
    $routes->post('account/password', 'Account::updatePassword');
});
