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
    $tanggalFilter = $this->request->getGet('tanggal') ?? '';
    $sortColumn = $this->request->getGet('sort-by') ?? 'nama';
    $sortOrder = $this->request->getGet('sort-order') ?? 'asc';

    // Default value
    $data = [
      'pageTitle' => 'Pengambilan Makanan',
      'data' => [], // Default array kosong
      'search' => $search,
      'kelasFilter' => $kelasFilter,
      'tanggalFilter' => $tanggalFilter,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'currentFilters' => []
    ];

    // Jalankan query ketika filter kelas telah dipilih
    if (!empty($kelasFilter)) {
      $query = $this->foodPickupModel
        ->select('food_pickups.*, students.nama_lengkap as nama_siswa, students.kelas, users.nama_lengkap as nama_operator')
        ->join('students', 'students.id = food_pickups.student_id', 'right')
        ->join('users', 'users.id = food_pickups.user_id', 'left');

      // Terapkan filter karena kita tahu $kelasFilter tidak kosong
      $query = $query->where('students.kelas', $kelasFilter);

      if (!empty($tanggalFilter)) {
        $query = $query->where('DATE(food_pickups.created_at)', $tanggalFilter);
      }

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

      // Isi/Timpa $data dengan hasil query
      $data['data'] = $query->get()->getResultArray();
      $data['currentFilters'] = array_filter($currentFilters);
    }

    // dd($data['data']);

    return view('pages/food_pickup.php', $data);
  }
}
