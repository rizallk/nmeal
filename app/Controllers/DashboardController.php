<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    protected $userRole;

    public function __construct()
    {
        $this->userRole = session()->get('userRole');
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];

        if ($this->userRole == 'admin' || $this->userRole == 'guru') {
            return view('pages/dashboard/admin', $data);
        } else if ($this->userRole == 'ortu') {
            return view('pages/dashboard/ortu', $data);
        }

        return '';
    }
}
