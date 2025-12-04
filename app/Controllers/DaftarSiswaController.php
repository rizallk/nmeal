<?php

namespace App\Controllers;

use App\Models\AllergenModel;
use App\Models\StudentAllergenModel;
use App\Models\StudentModel;
use App\Models\UserModel;

class DaftarSiswaController extends BaseController
{
  protected $studentModel;
  protected $userModel;
  protected $allergenModel;
  protected $studentAllergenModel;
  protected $userRole;

  public function __construct()
  {
    $this->studentModel = new StudentModel();
    $this->userModel = new UserModel();
    $this->allergenModel = new AllergenModel();
    $this->studentAllergenModel = new StudentAllergenModel();
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
    $query = $this->studentModel
      ->select('students.*, GROUP_CONCAT(allergens.name SEPARATOR ", ") as allergens')
      ->join('student_allergens', 'student_allergens.student_id = students.id', 'left')
      ->join('allergens', 'allergens.id = student_allergens.allergen_id', 'left')
      ->groupBy('students.id');

    if (!empty($kelasFilter)) {
      $query = $query->where('students.kelas', $kelasFilter);
    }

    // Logika Search
    if (!empty($search)) {
      $query = $query->groupStart()
        ->like('students.nama_lengkap', $search)
        ->orLike('students.nis', $search)
        ->orLike('students.kelas', $search)
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
      'allergens' => $this->allergenModel->select('id, name')->findAll()
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

    $studentId = $this->studentModel->getInsertID();

    $allergens = $this->request->getPost('allergens');

    foreach ($allergens as $allergenId) {
      $dataAllergenBatch[] = [
        'student_id'  => $studentId,
        'allergen_id' => $allergenId
      ];
    }

    if ($allergens && is_array($allergens)) {
      $dataAllergenBatch = [];
      foreach ($allergens as $allergenId) {
        $dataAllergenBatch[] = [
          'student_id'  => $studentId,
          'allergen_id' => $allergenId
        ];
      }

      if (!empty($dataAllergenBatch)) {
        $this->studentAllergenModel->insertBatch($dataAllergenBatch);
      }
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
      'allergens' => $this->allergenModel->select('id, name')->findAll(),
      'studentAllergens' => $this->studentAllergenModel->where('student_id', $id)->findAll()
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

    $newNIS = $this->request->getPost('nis');
    $oldNIS = $this->request->getPost('old_nis');

    // Data Student
    $dataStudent = [
      'id' => $id,
      'nama_lengkap' => $this->request->getPost('nama_lengkap'),
      'kelas' => $this->request->getPost('kelas'),
    ];

    if ($newNIS !== $oldNIS) {
      $dataStudent['nis'] = $newNIS;
    };

    if (!$this->studentModel->save($dataStudent)) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('validation', $this->studentModel->errors());
    }

    // Data alergen siswa
    $this->studentAllergenModel->where('student_id', $id)->delete();
    $allergens = $this->request->getPost('allergens');

    if ($allergens && is_array($allergens)) {
      $dataAllergenBatch = [];
      foreach ($allergens as $allergenId) {
        $dataAllergenBatch[] = [
          'student_id'  => $id,
          'allergen_id' => $allergenId
        ];
      }

      if (!empty($dataAllergenBatch)) {
        $this->studentAllergenModel->insertBatch($dataAllergenBatch);
      }
    }

    // Data User
    $dataUser = [
      'nama_lengkap' => $dataStudent['nama_lengkap'],
    ];

    if ($newNIS !== $oldNIS) {
      $dataUser['username'] = $dataStudent['nis'];
      $datauser['password'] = $dataStudent['nis'] . '@' . $dataStudent['kelas'];
    };

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
