<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
  protected $userModel;
  protected $userId;

  public function __construct()
  {
    $this->userModel = new UserModel();
    $this->userId = session()->get('userId');
  }

  public function index()
  {
    $profile = $this->userModel->find($this->userId);

    if (!$profile) {
      return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

    $isOrtu = $profile['role'] == 'ortu' ? 'OrTu ' : '';

    $data = [
      'pageTitle' => 'Profile - ' . $isOrtu . $profile['nama_lengkap'],
      'profile'  => $profile,
      // 'validation' => \Config\Services::validation(),
    ];

    return view('pages/profile', $data);
  }


  public function update(int $id)
  {
    $profile = $this->userModel->find($id);

    if (!$profile) {
      return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

    $password = $this->request->getPost('password');
    $username = $this->request->getPost('username');
    $usernameLama = $this->request->getPost('username_lama');

    $data = [
      'id' => $id,
      'nama_lengkap' => $this->request->getPost('nama_lengkap'),
      'role' => $this->request->getPost('role'),
    ];

    $fotoFile = $this->request->getFile('foto');
    $fotoLama = $this->request->getPost('foto_lama');
    $fotoName = '';

    if ($fotoFile->isValid()) {
      $fotoName = $fotoFile->getRandomName();
      $data['foto'] = $fotoName;
    }

    if (!empty($password)) {
      $data['password'] = $password;
    }

    if ($username !== $usernameLama) {
      $data['username'] = $username;
    }

    if (!$this->userModel->save($data)) {
      if ($fotoFile->isValid()) {
        $fotoFile = '';
      }
      return redirect()->back()->withInput()->with('validation', $this->userModel->errors());
    }

    if ($fotoFile->isValid()) {
      $fotoFile->move(ROOTPATH . 'public/uploads/foto_user', $fotoName);

      if ($fotoLama && file_exists(ROOTPATH . 'public/uploads/foto_user/' . $fotoLama)) {
        unlink(ROOTPATH . 'public/uploads/foto_user/' . $fotoLama);
      }
    }

    $namaUpdated = $this->request->getPost('nama_lengkap');

    return redirect()->to(site_url('profile'))->with('success', 'Profile berhasil diupdate.');
  }
}
