<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function index(): string
    {
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
        $model = new UserModel();
        $session = session();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $model->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $ses_data = [
                    'nama' => $user['nama_lengkap'],
                    'userRole' => $user['role'],
                    'username' => $user['username'],
                    'userFoto' => $user['foto'],
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
