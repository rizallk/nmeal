<?php

namespace App\Controllers;

use App\Models\FoodPickupModel;
use App\Models\StudentModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class FoodPickupController extends BaseController
{
  protected $foodPickupModel;
  protected $studentModel;

  public function __construct()
  {
    $this->foodPickupModel = new FoodPickupModel();
    $this->studentModel = new StudentModel();
  }

  private function getPickupData($kelasFilter, $tanggalFilter, $search, $sortColumn, $sortOrder)
  {
    if (empty($kelasFilter) || $tanggalFilter > date("Y-m-d")) {
      return [];
    }

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
                foods.name as menu_makanan,
                food_pickups.catatan, 
                users.nama_lengkap as nama_operator
            ')
      ->join('food_pickups', $onClause, 'left')
      ->join('users', 'users.id = food_pickups.user_id', 'left')
      ->join('student_foods', 'student_foods.student_id = students.id', 'left')
      ->join('foods', 'foods.id = student_foods.food_id', 'left');

    $query = $query->where('students.kelas', $kelasFilter);

    if (!empty($search)) {
      $query = $query->groupStart()
        ->like('students.nama_lengkap', $search)
        ->orLike('food_pickups.status', $search)
        ->groupEnd();
    }

    // Logika Sorting
    $validSortColumns = ['nama_lengkap' => 'students.nama_lengkap'];
    if (array_key_exists($sortColumn, $validSortColumns)) {
      $query = $query->orderBy($validSortColumns[$sortColumn], $sortOrder);
    } else {
      $query = $query->orderBy('students.nama_lengkap', 'asc');
    }

    return $query->get()->getResultArray();
  }

  public function index()
  {
    $search = $this->request->getGet('search') ?? '';
    $kelasFilter = $this->request->getGet('kelas') ?? '';
    $tanggalFilter = $this->request->getGet('tanggal') ?? date("Y-m-d");
    $sortColumn = $this->request->getGet('sort-by') ?? 'nama_lengkap';
    $sortOrder = $this->request->getGet('sort-order') ?? 'asc';

    $isEditable = $tanggalFilter == date("Y-m-d") ? true : false;

    $resultData = $this->getPickupData($kelasFilter, $tanggalFilter, $search, $sortColumn, $sortOrder);

    $currentFilters = [
      'search' => $search,
      'kelas' => $kelasFilter,
      'tanggal' => $tanggalFilter,
      'sort-by' => $sortColumn,
      'sort-order' => $sortOrder
    ];

    $data = [
      'pageTitle' => 'Pengambilan Makanan',
      'data' => $resultData,
      'search' => $search,
      'kelasFilter' => $kelasFilter,
      'tanggalFilter' => $tanggalFilter,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'currentFilters' => array_filter($currentFilters),
      'isEditable' => $isEditable
    ];

    return view('pages/food_pickup/index', $data);
  }

  public function exportPdf()
  {
    $search = $this->request->getGet('search') ?? '';
    $kelasFilter = $this->request->getGet('kelas') ?? '';
    $tanggalFilter = $this->request->getGet('tanggal') ?? date("Y-m-d");
    $sortColumn = $this->request->getGet('sort-by') ?? 'nama_lengkap';
    $sortOrder = $this->request->getGet('sort-order') ?? 'asc';

    $data = $this->getPickupData($kelasFilter, $tanggalFilter, $search, $sortColumn, $sortOrder);

    if (empty($data)) {
      return redirect()->back()->with('error', 'Tidak ada data untuk dicetak.');
    }

    $viewData = [
      'data' => $data,
      'kelas' => $kelasFilter,
      'tanggal' => $tanggalFilter
    ];

    $html = view('pages/food_pickup/export_pdf', $viewData);

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $filename = 'Laporan Pengambilan Makanan_Kelas_' . $kelasFilter . '_' . $tanggalFilter . '.pdf';

    $dompdf->stream($filename, ["Attachment" => false]); // Set true jika ingin langsung download
  }

  public function save()
  {
    $submitted_student_ids = $this->request->getPost('student_ids') ?? [];
    $tanggal = $this->request->getPost('tanggal');
    $kelas = $this->request->getPost('kelas');
    $catatan_list = $this->request->getPost('catatan') ?? [];

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
        $existing_map[$pickup['student_id']] = $pickup;
      }

      $insert_data = [];
      $update_data = [];
      $ids_to_delete = [];

      $timestamp = $tanggal . ' ' . date('H:i:s');

      foreach ($all_students_in_class as $student_id) {
        $is_checked_form = in_array($student_id, $submitted_student_ids);
        $is_existing = array_key_exists($student_id, $existing_map);
        $catatan_siswa = !empty($catatan_list[$student_id]) ? $catatan_list[$student_id] : null;

        if ($is_checked_form || $catatan_siswa) {
          $final_status = $is_checked_form ? 1 : 0;

          if ($is_existing) {
            $update_data[] = [
              'id' => $existing_map[$student_id]['id'],
              'status' => $final_status,
              'user_id' => $operator_id,
              'catatan' => $catatan_siswa,
              'updated_at' => $timestamp
            ];
          } else {
            $insert_data[] = [
              'student_id' => $student_id,
              'user_id' => $operator_id,
              'status' => $final_status,
              'catatan' => $catatan_siswa,
              'created_at' => $timestamp,
              'updated_at' => $timestamp
            ];
          }
        } else {
          if ($is_existing) {
            $ids_to_delete[] = $existing_map[$student_id]['id'];
          }
        }
      }

      if (!empty($insert_data)) {
        $this->foodPickupModel->insertBatch($insert_data);
      }

      if (!empty($update_data)) {
        $this->foodPickupModel->updateBatch($update_data, 'id');
      }

      if (!empty($ids_to_delete)) {
        $this->foodPickupModel->delete($ids_to_delete);
      }

      $this->foodPickupModel->db->transCommit();
      return redirect()->back()->with('success', 'Data berhasil disimpan.');
    } catch (\Exception $e) {
      $this->foodPickupModel->db->transRollback();
      return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
  }
}
