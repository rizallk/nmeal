<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function index(): string
    {
        $role = session()->get('userRole');

        $data = [
            'pageTitle' => 'Dashboard',
        ];

        if ($role == 'admin' || $role == 'guru') {
            return view('pages/dashboard/admin', $data);
        } else if ($role == 'ortu') {
            return view('pages/dashboard/ortu', $data);
        }

        return '';
    }
}
