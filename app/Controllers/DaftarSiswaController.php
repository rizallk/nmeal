<?php

namespace App\Controllers;

use App\Models\FoodModel;
use App\Models\StudentFoodModel;
use App\Models\StudentModel;
use App\Models\UserModel;

class DaftarSiswaController extends BaseController
{
  protected $studentModel;
  protected $foodModel;
  protected $studentFoodModel;
  protected $userModel;
  protected $userRole;

  public function __construct()
  {
    $this->studentModel = new StudentModel();
    $this->foodModel = new FoodModel();
    $this->studentFoodModel = new StudentFoodModel();
    $this->userModel = new UserModel();
    $this->userRole = session()->get('userRole');
  }

  public function index()
  {
    if ($this->userRole != 'admin') return redirect()->back();

    $search = $this->request->getGet('search') ?? '';
    $kelasFilter = $this->request->getGet('kelas') ?? '';
    $sortColumn = $this->request->getGet('sort-by') ?? 'nama_lengkap';
    $sortOrder = $this->request->getGet('sort-order') ?? 'asc';

    $perPage = 20; // default jumlah data yang muncul per-page

    // Dapatkan halaman saat ini dari URL, defaultnya adalah 1
    $currentPage = $this->request->getGet('page') ?? 1;
    $startNumber = ($currentPage - 1) * $perPage; // Logika numbering
    $query = $this->studentModel->select('students.id, students.nis, students.nama_lengkap, students.kelas, foods.name as menu_makanan')
      ->join('student_foods', 'student_foods.student_id = students.id', 'left')
      ->join('foods', 'foods.id = student_foods.food_id', 'left');

    if (!empty($kelasFilter)) {
      $query = $query->where('kelas', $kelasFilter);
    }

    // Logika Search
    if (!empty($search)) {
      $query = $query->groupStart()
        ->like('nama_lengkap', $search)
        ->orLike('nis', $search)
        ->orLike('kelas', $search)
        ->groupEnd();
    }

    // Logika Sorting
    $validSortColumns = ['nama_lengkap', 'nis'];
    if (in_array($sortColumn, $validSortColumns)) {
      $query = $query->orderBy($sortColumn, $sortOrder);
    }

    $currentFilters = [
      'search' => $search,
      'kelas' => $kelasFilter,
      'sort-by' => $sortColumn,
      'sort-order' => $sortOrder
    ];
    $currentFilters = array_filter($currentFilters);

    $data = [
      'pageTitle' => 'Daftar Siswa',
      'daftarSiswa' => $query->paginate($perPage, 'default', $currentPage),
      'pager' => $this->studentModel->pager,
      'search' => $search,
      'kelasFilter' => $kelasFilter,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'startNumber' => $startNumber,
      'currentFilters' => $currentFilters
    ];

    $data['pager']->setPath('daftar-siswa', 'default');

    return view('pages/daftar_siswa/index', $data);
  }

  public function registerView()
  {
    if ($this->userRole !== 'admin') return redirect()->back();

    $data = [
      'pageTitle' => 'Tambah Siswa',
      'daftarMenuMakanan' => $this->foodModel->select('id, name')->findAll()
    ];

    return view('pages/daftar_siswa/tambah', $data);
  }

  public function register()
  {
    $db = \Config\Database::connect(); // Instance database
    $db->transBegin();

    $dataStudent = [
      'nis' => $this->request->getPost('nis'),
      'nama_lengkap' => $this->request->getPost('nama_lengkap'),
      'kelas' => $this->request->getPost('kelas'),
    ];

    if (!$this->studentModel->save($dataStudent)) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('validation', $this->studentModel->errors());
    }

    $dataStudentFood = [
      'student_id' => $this->studentModel->insertID(),
      'food_id' => $this->request->getPost('menu_makanan')
    ];

    if (!$this->studentFoodModel->save($dataStudentFood)) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('validation', $this->studentFoodModel->errors());
    }

    if ($this->request->getPost('create_parent_account')) {
      $password = $dataStudent['nis'] . '@' . $dataStudent['kelas'];

      $dataUser = [
        'nama_lengkap' => $dataStudent['nama_lengkap'],
        'role' => 'ortu',
        'username' => $dataStudent['nis'],
        'password' => $password
      ];

      if (!$this->userModel->save($dataUser)) {
        $db->transRollback();
        return redirect()->back()->withInput()->with('validation', $this->userModel->errors());
      }
    }

    if ($db->transStatus() === false) {
      $db->transRollback();
      return redirect()->back()->with('error', 'Gagal menambahkan data siswa. Terjadi masalah saat menyimpan data.');
    }

    // Komit (Simpan Permanen) jika semua berhasil
    $db->transCommit();

    return redirect()->to('/tambah-siswa')->with('success', 'Tambah siswa "' . $this->request->getPost('nama_lengkap') . '" berhasil!');
  }

  public function edit(int $id)
  {
    $siswa = $this->studentModel->find($id);

    if (!$siswa) {
      return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
    }

    $data = [
      'pageTitle' => 'Edit Siswa - ' . $siswa['nama_lengkap'],
      'siswa'  => $siswa,
      'menuMakananSelected' => $this->studentFoodModel->where('student_id', $id)->first(),
      'daftarMenuMakanan' => $this->foodModel->select('id, name')->findAll()
    ];

    return view('pages/daftar_siswa/edit', $data);
  }

  public function update(int $id)
  {
    $siswa = $this->studentModel->find($id);

    if (!$siswa) {
      return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
    }

    $db = \Config\Database::connect(); // Instance database
    $db->transBegin();

    // Data Student
    $dataStudent = [
      'id' => $id,
      'nis' => $this->request->getPost('nis'),
      'nama_lengkap' => $this->request->getPost('nama_lengkap'),
      'kelas' => $this->request->getPost('kelas'),
    ];

    if (!$this->studentModel->save($dataStudent)) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('validation', $this->studentModel->errors());
    }

    // Data Student Food
    $newFoodId = $this->request->getPost('menu_makanan');
    $dataStudentFood = [
      'student_id' => $id,
      'food_id'    => $newFoodId
    ];

    if (!$this->studentFoodModel->validate($dataStudentFood)) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('validation', $this->studentFoodModel->errors());
    }

    $existingData = $this->studentFoodModel->where('student_id', $id)->first();
    if ($existingData) {
      $this->studentFoodModel
        ->where('student_id', $id)
        ->set(['food_id' => $newFoodId])
        ->update();
    } else {
      $this->studentFoodModel->insert($dataStudentFood);
    }

    // Data User
    $dataUser = [
      'nama_lengkap' => $dataStudent['nama_lengkap'],
      'username' => $dataStudent['nis'],
      'password' => $dataStudent['nis'] . '@' . $dataStudent['kelas']
    ];
    $oldNIS = $this->request->getPost('old_nis');

    $existingData = $this->userModel->where('username', $oldNIS)->first();
    if ($existingData) {
      $this->userModel
        ->where('username', $oldNIS)
        ->set($dataUser)
        ->update();
    }

    // ===============================

    if ($db->transStatus() === false) {
      $db->transRollback();
      return redirect()->back()->with('error', 'Gagal mengubah data siswa. Terjadi masalah saat menyimpan data.');
    }

    // Komit (Simpan Permanen) jika semua berhasil
    $db->transCommit();

    $namaUpdated = $this->request->getPost('nama_lengkap');

    return redirect()->back()->with('success', 'Siswa "' . esc($namaUpdated) . '" berhasil diupdate.');
  }

  public function delete(int $id)
  {
    if ($this->request->getMethod() !== 'POST') {
      return redirect()->back()->with('error', 'Metode penghapusan tidak valid. Harap gunakan tombol Hapus.');
    }

    $db = \Config\Database::connect(); // Instance database
    $db->transBegin();

    $siswa = $this->studentModel->find($id);

    if (!$siswa) {
      return redirect()->back()->with('error', 'siswa tidak ditemukan.');
    }

    $this->studentModel->delete($id);

    $user = $this->userModel->where('username', $siswa['nis'])->first();
    if ($user) {
      $this->userModel->where('username', $siswa['nis'])->delete();
    }

    if ($db->transStatus() === false) {
      $db->transRollback();
      return redirect()->back()->with('error', 'Gagal mengubah data siswa. Terjadi masalah saat menyimpan data.');
    }

    // Komit (Simpan Permanen) jika semua berhasil
    $db->transCommit();

    return redirect()->back()->with('success', 'Siswa "' . esc($siswa['nama_lengkap']) . '" berhasil dihapus.');
  }
}
