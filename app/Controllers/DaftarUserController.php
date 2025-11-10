<?php

namespace App\Controllers;

use App\Models\UserModel;

class DaftarUserController extends BaseController
{
  protected $userModel;

  public function __construct()
  {
    $this->userModel = new UserModel();
  }

  public function index()
  {
    $search = $this->request->getGet('search') ?? '';
    $roleFilter = $this->request->getGet('role') ?? '';
    $sortColumn = $this->request->getGet('sort-by') ?? 'created_at';
    $sortOrder = $this->request->getGet('sort-order') ?? 'desc';

    $perPage = 10; // default jumlah data yang muncul per-page

    // Dapatkan halaman saat ini dari URL, defaultnya adalah 1
    $currentPage = $this->request->getGet('page') ?? 1;
    $startNumber = ($currentPage - 1) * $perPage; // Logika numbering
    $query = $this->userModel; // query builder

    if (!empty($roleFilter)) {
      $query = $query->where('role', $roleFilter);
    }

    // Logika Search
    if (!empty($search)) {
      $query = $query->groupStart()
        ->like('nama', $search)
        ->orLike('username', $search)
        ->groupEnd();
    }

    // Logika Sorting
    $validSortColumns = ['nama', 'role', 'username', 'created_at'];
    if (in_array($sortColumn, $validSortColumns)) {
      $query = $query->orderBy($sortColumn, $sortOrder);
    }

    $currentFilters = [
      'search' => $search,
      'role' => $roleFilter
    ];
    $currentFilters = array_filter($currentFilters);

    $data = [
      'pageTitle' => 'Daftar User',
      'daftarUser' => $query->paginate($perPage, 'default'),
      'pager' => $this->userModel->pager,
      'search' => $search,
      'roleFilter' => $roleFilter,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'startNumber' => $startNumber,
      'currentFilters' => $currentFilters
    ];

    return view('pages/daftar_user/index', $data);
  }

  public function registerView()
  {
    $data = [
      'pageTitle' => 'Tambah User',
    ];

    return view('pages/daftar_user/tambah', $data);
  }

  public function register()
  {
    $data = [
      'nama' => $this->request->getPost('nama'),
      'role' => $this->request->getPost('role'),
      'username' => $this->request->getPost('username'),
      'password' => $this->request->getPost('password'),
    ];

    $fotoFile = $this->request->getFile('foto');
    $fotoName = '';

    if ($fotoFile->isValid()) {
      $fotoName = $fotoFile->getRandomName();
      $data['foto'] = $fotoName;
    }

    if (!$this->userModel->save($data)) {
      if ($fotoFile->isValid()) {
        $fotoFile = '';
      }
      return redirect()->back()->withInput()->with('validation', $this->userModel->errors());
    }

    $fotoFile->move(ROOTPATH . 'public/uploads/foto_user', $fotoName);

    // Jika registrasi berhasil
    return redirect()->to('/tambah-user')->with('success', 'Tambah user "' . $this->request->getPost('nama') . '" berhasil!');
  }

  public function edit(int $id)
  {
    $user = $this->userModel->find($id);

    if (!$user) {
      return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

    $data = [
      'pageTitle' => 'Edit User - ' . $user['nama'],
      'user'  => $user,
      // 'validation' => \Config\Services::validation(),
    ];

    return view('pages/daftar_user/edit', $data);
  }

  public function update(int $id)
  {
    $user = $this->userModel->find($id);

    if (!$user) {
      return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

    $password = $this->request->getPost('password');
    $username = $this->request->getPost('username');
    $usernameLama = $this->request->getPost('username_lama');

    $data = [
      'id' => $id,
      'nama' => $this->request->getPost('nama'),
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

    $namaUpdated = $this->request->getPost('nama');

    return redirect()->to(site_url('edit-user/' . $id))->with('success', 'User "' . esc($namaUpdated) . '" berhasil diupdate.');
  }

  public function delete(int $id)
  {
    // Pastikan method yang digunakan adalah POST dari form
    if ($this->request->getMethod() !== 'POST') {
      return redirect()->back()->with('error', 'Metode penghapusan tidak valid. Harap gunakan tombol Hapus.');
    }

    $user = $this->userModel->find($id);

    if (!$user) {
      return redirect()->back()->with('error', 'User tidak ditemukan.');
    }

    $fotoFile = $user['foto'];
    if ($fotoFile) {
      $filePath = ROOTPATH . 'public/uploads/foto_user/' . $fotoFile;
      if (file_exists($filePath)) {
        unlink($filePath);
      }
    }

    // Lakukan soft delete
    $this->userModel->delete($id);

    return redirect()->to(site_url('daftar-user'))->with('success', 'User "' . esc($user['nama']) . '" berhasil dihapus.');
  }
}
