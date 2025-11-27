<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    protected $userModel;
    protected $studentModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->studentModel = new StudentModel();
    }

    public function index()
    {
        $loggedIn = session()->get('loggedIn');

        if ($loggedIn) {
            return redirect()->to('/dashboard');
        }

        return view('pages/login');
    }

    public function login()
    {
        // Validasi field
        $rules = [
            'username' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'username' => [
                'required' => 'Username wajib diisi.',
            ],
            'password' => [
                'required' => 'Password wajib diisi.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('validation', $this->validator->getErrors());
        }

        // Proses otentikasi pengguna
        $session = session();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $this->userModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $studentId = $this->studentModel->where('nis', $user['username'])->first();

                $ses_data = [
                    'nama' => $user['nama_lengkap'],
                    'userId' => $user['id'],
                    'userRole' => $user['role'],
                    'username' => $user['username'],
                    'userFoto' => $user['foto'],
                    'studentId' => $user['role'] == 'ortu' ? $studentId['id'] : null,
                    'loggedIn' => TRUE
                ];
                $session->set($ses_data);

                return redirect()->to('/dashboard');
            } else {
                $session->setFlashdata('error', 'Username atau password salah.');
                return redirect()->back()->withInput();
            }
        } else {
            $session->setFlashdata('error', 'Username atau password salah.');
            return redirect()->back()->withInput();
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/');
    }
}
