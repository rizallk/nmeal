<?php

namespace App\Controllers;

use App\Models\ActivityModel;

class AktivitasTerkiniController extends BaseController
{
  protected $activityModel;

  public function __construct()
  {
    $this->activityModel = new ActivityModel();
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
      'pageTitle' => 'Aktivitas Terkini',
      'data' => [], // Default array kosong
      'pager' => null, // Default pager null
      'search' => $search,
      'kelasFilter' => $kelasFilter,
      'tanggalFilter' => $tanggalFilter,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'startNumber' => 0,
      'currentFilters' => []
    ];

    // Jalankan query ketika filter kelas telah dipilih
    if (!empty($kelasFilter)) {
      $perPage = 10;
      $currentPage = $this->request->getGet('page') ?? 1;
      $startNumber = ($currentPage - 1) * $perPage;

      $query = $this->activityModel
        ->select('activities.*, students.nama, students.kelas')
        ->join('students', 'students.id = activities.student_id', 'left');

      // Terapkan filter karena kita tahu $kelasFilter tidak kosong
      $query = $query->where('students.kelas', $kelasFilter);

      if (!empty($tanggalFilter)) {
        $query = $query->where('DATE(activities.created_at)', $tanggalFilter);
      }

      if (!empty($search)) {
        $query = $query->groupStart()
          ->like('students.nama', $search)
          ->orLike('activities.status', $search)
          ->groupEnd();
      }

      // Logika Sorting (Perbaikan: gunakan array_key_exists)
      $validSortColumns = ['nama' => 'students.nama'];
      if (array_key_exists($sortColumn, $validSortColumns)) {
        $query = $query->orderBy($validSortColumns[$sortColumn], $sortOrder);
      } else {
        // Default sort
        $query = $query->orderBy('students.nama', 'asc');
      }

      $currentFilters = [
        'search' => $search,
        'kelas' => $kelasFilter,
        'tanggal' => $tanggalFilter
      ];

      // Isi/Timpa $data dengan hasil query
      $data['data'] = $query->paginate($perPage, 'default');
      $data['pager'] = $this->activityModel->pager;
      $data['startNumber'] = $startNumber;
      $data['currentFilters'] = array_filter($currentFilters);
    }

    return view('pages/aktivitas_terkini.php', $data);
  }
}
