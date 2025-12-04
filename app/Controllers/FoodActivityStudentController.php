<?php

namespace App\Controllers;

use App\Models\FoodModel;
use App\Models\FoodPickupModel;
use App\Models\StudentModel;
use App\Models\UserModel;

class FoodActivityStudentController extends BaseController
{
  protected $studentModel;
  protected $foodModel;
  protected $userModel;
  protected $foodPickupModel;
  protected $userRole;

  public function __construct()
  {
    $this->studentModel = new StudentModel();
    $this->foodModel = new FoodModel();
    $this->userModel = new UserModel();
    $this->foodPickupModel = new FoodPickupModel();
    $this->userRole = session()->get('userRole');
  }

  public function index()
  {
    if ($this->userRole != 'ortu') return redirect()->back();

    $search = $this->request->getGet('search') ?? '';
    $tanggalFilter = $this->request->getGet('tanggal') ?? '';
    $sortColumn = $this->request->getGet('sort-by') ?? 'created_at';
    $sortOrder = $this->request->getGet('sort-order') ?? 'desc';

    $perPage = 20; // default jumlah data yang muncul per-page
    $currentPage = $this->request->getGet('page') ?? 1;
    $startNumber = ($currentPage - 1) * $perPage; // Logika numbering

    $studentData = $this->studentModel->where('nis', session()->get('username'))->select('id, kelas')->first();

    if (!$studentData) {
      return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
    }

    $query = $this->foodPickupModel->where('food_pickups.student_id', $studentData['id'])
      ->select('food_pickups.status, food_pickups.catatan, food_pickups.created_at, foods.name as menu_makanan')
      ->join('foods', 'foods.id = food_pickups.food_id', 'left');

    // Logika Search
    if (!empty($search)) {
      $query = $query->groupStart()
        ->like('foods.name', $search)
        ->orLike('food_pickups.catatan', $search)
        ->groupEnd();
    }

    if (!empty($tanggalFilter)) {
      $query->where('DATE(food_pickups.created_at)', $tanggalFilter);
    }

    // Logika Sorting
    $validSortColumns = ['status', 'created_at'];
    if (in_array($sortColumn, $validSortColumns)) {
      $query = $query->orderBy('food_pickups.' . $sortColumn, $sortOrder);
    }

    $currentFilters = [
      'search' => $search,
      'tanggal' => $tanggalFilter,
      'sort-by' => $sortColumn,
      'sort-order' => $sortOrder
    ];
    $currentFilters = array_filter($currentFilters);

    $data = [
      'pageTitle' => 'Aktivitas Makan',
      'data' => $query->paginate($perPage, 'default', $currentPage),
      'pager' => $this->foodPickupModel->pager,
      'search' => $search,
      'kelas' => $studentData['kelas'],
      'tanggalFilter' => $tanggalFilter,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'startNumber' => $startNumber,
      'currentFilters' => $currentFilters
    ];

    $data['pager']->setPath('food-activity', 'default');

    return view('pages/food_activity_student', $data);
  }
}
