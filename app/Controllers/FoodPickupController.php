<?php

namespace App\Controllers;

use App\Models\FoodPickupModel;
use App\Models\StudentModel;

class FoodPickupController extends BaseController
{
  protected $foodPickupModel;
  protected $studentModel;

  public function __construct()
  {
    $this->foodPickupModel = new FoodPickupModel();
    $this->studentModel = new StudentModel();
  }

  public function index()
  {
    $search = $this->request->getGet('search') ?? '';
    $kelasFilter = $this->request->getGet('kelas') ?? '';
    $tanggalFilter = $this->request->getGet('tanggal') ?? date("Y-m-d");
    $sortColumn = $this->request->getGet('sort-by') ?? 'nama';
    $sortOrder = $this->request->getGet('sort-order') ?? 'asc';

    $isEditable = $tanggalFilter == date("Y-m-d") ? true : false;

    // Default value
    $data = [
      'pageTitle' => 'Pengambilan Makanan',
      'data' => [], // Default array kosong
      'search' => $search,
      'kelasFilter' => $kelasFilter,
      'tanggalFilter' => $tanggalFilter,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'currentFilters' => [],
      'isEditable' => $isEditable
    ];

    // Jalankan query ketika filter kelas telah dipilih
    if (!empty($kelasFilter) && $tanggalFilter <= date("Y-m-d")) {
      $query = $this->studentModel;

      $onClause = 'food_pickups.student_id = students.id';
      if (!empty($tanggalFilter)) {
        $onClause .= " AND DATE(food_pickups.created_at) = " . $this->studentModel->db->escape($tanggalFilter);
      }

      $query = $query
        ->select('
          students.id as student_id,
          students.nama_lengkap as nama_siswa, 
          students.kelas, 
          food_pickups.status, 
          food_pickups.laporan, 
          food_pickups.created_at, 
          users.nama_lengkap as nama_operator
        ')
        ->join('food_pickups', $onClause, 'left')
        ->join('users', 'users.id = food_pickups.user_id', 'left');

      $query = $query->where('students.kelas', $kelasFilter);

      if (!empty($search)) {
        $query = $query->groupStart()
          ->like('students.nama_lengkap', $search)
          ->orLike('food_pickups.status', $search)
          ->groupEnd();
      }

      // Logika Sorting
      $validSortColumns = ['nama' => 'students.nama_lengkap'];
      if (array_key_exists($sortColumn, $validSortColumns)) {
        $query = $query->orderBy($validSortColumns[$sortColumn], $sortOrder);
      } else {
        // Default sort
        $query = $query->orderBy('students.nama_lengkap', 'asc');
      }

      $currentFilters = [
        'search' => $search,
        'kelas' => $kelasFilter,
        'tanggal' => $tanggalFilter,
        'sort-by' => $sortColumn,
        'sort-order' => $sortOrder
      ];

      $data['data'] = $query->get()->getResultArray();
      $data['currentFilters'] = array_filter($currentFilters);
    }

    // dd($data['data']);

    return view('pages/food_pickup.php', $data);
  }

  public function save()
  {
    $submitted_student_ids = $this->request->getPost('student_ids') ?? [];
    $tanggal = $this->request->getPost('tanggal');
    $kelas = $this->request->getPost('kelas');

    $isEditable = ($tanggal === date("Y-m-d"));
    if (!$isEditable) {
      return redirect()->back()->with('error', 'Tidak dapat menyimpan data untuk yang bukan tanggal saat ini.');
    }

    $operator_id = session()->get('userId');
    if (empty($operator_id)) {
      return redirect()->back()->with('error', 'Anda harus login untuk menyimpan data.');
    }

    $all_students_in_class = $this->studentModel->where('kelas', $kelas)->findColumn('id') ?? [];

    if (empty($all_students_in_class)) {
      return redirect()->back()->with('error', 'Data siswa untuk kelas ini tidak ditemukan.');
    }

    // DB Transaction untuk keamanan
    $this->foodPickupModel->db->transStart();

    try {
      $existing_pickups = $this->foodPickupModel
        ->where('DATE(created_at)', $tanggal)
        ->whereIn('student_id', $all_students_in_class)
        ->findAll();

      $existing_map = [];
      foreach ($existing_pickups as $pickup) {
        $existing_map[$pickup['student_id']] = $pickup['id'];
      }

      $ids_to_insert = []; // Student ID yang akan di-INSERT
      $ids_to_update = []; // Pickup ID (PK) yang akan di-UPDATE
      $ids_to_delete = []; // Pickup ID (PK) yang akan di-DELETE

      foreach ($all_students_in_class as $student_id) {
        $is_checked = in_array($student_id, $submitted_student_ids);
        $is_existing = array_key_exists($student_id, $existing_map);

        if ($is_checked) {
          // Skenario: Siswa Dicentang
          if ($is_existing) {
            // Dicentang dan sudah ada -> UPDATE
            $ids_to_update[] = $existing_map[$student_id];
          } else {
            // Dicentang dan belum ada -> INSERT
            $ids_to_insert[] = $student_id;
          }
        } else {
          // Skenario: Siswa TIDAK Dicentang
          if ($is_existing) {
            // Tidak dicentang dan sudah ada -> DELETE (Sesuai permintaan)
            $ids_to_delete[] = $existing_map[$student_id];
          }
          // Tidak dicentang dan tidak ada -> Abaikan
        }
      }

      $timestamp = $tanggal . ' ' . date('H:i:s');

      $batch_insert_data = [];
      foreach ($ids_to_insert as $student_id) {
        $batch_insert_data[] = [
          'student_id' => $student_id,
          'user_id' => $operator_id,
          'status' => 1, // 1 = Sudah
          'laporan' => null,
          'created_at' => $timestamp,
          'updated_at' => $timestamp
        ];
      }
      if (!empty($batch_insert_data)) {
        $this->foodPickupModel->insertBatch($batch_insert_data);
      }

      if (!empty($ids_to_update)) {
        $this->foodPickupModel
          ->whereIn('id', $ids_to_update)
          ->set([
            'status' => 1,
            'user_id' => $operator_id,
            'updated_at' => $timestamp
          ])
          ->update();
      }

      if (!empty($ids_to_delete)) {
        $this->foodPickupModel
          ->whereIn('id', $ids_to_delete)
          ->delete();
      }

      $this->foodPickupModel->db->transCommit();

      return redirect()->back()->with('success', 'Data pengambilan makanan berhasil disimpan.');
    } catch (\Exception $e) {
      $this->foodPickupModel->db->transRollback();
      return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }
}
