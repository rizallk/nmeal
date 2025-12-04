<?php

namespace App\Controllers;

use App\Models\FoodModel;
use App\Models\FoodPickupModel;
use App\Models\PushSubscriptionModel;
use App\Models\StudentAllergenModel;
use App\Models\StudentModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class FoodPickupController extends BaseController
{
  protected $foodPickupModel;
  protected $studentModel;
  protected $foodModel;
  protected $studentAllergenModel;

  public function __construct()
  {
    $this->foodPickupModel = new FoodPickupModel();
    $this->studentModel = new StudentModel();
    $this->foodModel = new FoodModel();
    $this->studentAllergenModel = new StudentAllergenModel();
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
                food_pickups.food_id, 
                food_pickups.catatan, 
                users.nama_lengkap as nama_operator
            ')
      ->join('food_pickups', $onClause, 'left')
      ->join('users', 'users.id = food_pickups.user_id', 'left')
      ->join('foods', 'foods.id = food_pickups.food_id', 'left');

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
      'isEditable' => $isEditable,
      'daftarMenuMakanan' => $this->foodModel->select('id, name')->findAll()
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
    // Cek apakah request berupa JSON (dari fetch API/PWA)
    if ($this->request->isAJAX() || $this->request->getHeaderLine('Content-Type') == 'application/json') {
      $json = $this->request->getJSON(true); // Ambil raw body JSON sebagai array

      // Mapping data JSON ke variabel yang biasa dipakai
      $submitted_student_ids = $json['student_ids'] ?? [];
      $tanggal = $json['tanggal'];
      $kelas = $json['kelas'];
      $catatan_list = $json['catatan'] ?? [];
      $food_ids_list = $json['food_ids'] ?? [];
      $isJsonRequest = true;
    } else {
      // Fallback untuk submit biasa (Form standard)
      $submitted_student_ids = $this->request->getPost('student_ids') ?? [];
      $tanggal = $this->request->getPost('tanggal');
      $kelas = $this->request->getPost('kelas');
      $catatan_list = $this->request->getPost('catatan') ?? [];
      $food_ids_list = $this->request->getPost('food_ids') ?? [];
      $isJsonRequest = false;
    }

    // Validasi
    $isEditable = ($tanggal === date("Y-m-d"));
    if (!$isEditable) {
      $msg = 'Tidak dapat menyimpan data untuk yang bukan tanggal saat ini.';
      return $isJsonRequest ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->with('error', $msg);
    }

    $operator_id = session()->get('userId');
    if (empty($operator_id)) {
      $msg = 'Anda harus login untuk menyimpan data.';
      return $isJsonRequest ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->with('error', $msg);
    }

    // Ambil data siswa
    $all_students_in_class = $this->studentModel->where('kelas', $kelas)->findColumn('id') ?? [];
    if (empty($all_students_in_class)) {
      $msg = 'Data siswa untuk kelas ini tidak ditemukan.';
      return $isJsonRequest ? $this->response->setJSON(['status' => 'error', 'message' => $msg]) : redirect()->back()->with('error', $msg);
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

      $studentsToNotify = [];

      $timestamp = $tanggal . ' ' . date('H:i:s');

      foreach ($all_students_in_class as $student_id) {
        $is_checked = in_array($student_id, $submitted_student_ids);
        $is_existing = array_key_exists($student_id, $existing_map);
        $catatan_siswa = !empty($catatan_list[$student_id]) ? $catatan_list[$student_id] : null;

        $raw_food_id = $food_ids_list[$student_id] ?? null;
        $food_id_siswa = ($raw_food_id === '' || $raw_food_id === '0') ? null : $raw_food_id;

        if ($is_checked) {
          $studentsToNotify[] = $student_id;

          if ($is_existing) {
            $update_data[] = [
              'id' => $existing_map[$student_id]['id'],
              'user_id' => $operator_id,
              'food_id' => $food_id_siswa,
              'status' => 1,
              'catatan' => $catatan_siswa,
              'updated_at' => $timestamp
            ];
          } else {
            $insert_data[] = [
              'student_id' => $student_id,
              'user_id' => $operator_id,
              'food_id' => $food_id_siswa,
              'status' => 1,
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

      if (!empty($insert_data)) $this->foodPickupModel->insertBatch($insert_data);
      if (!empty($update_data)) $this->foodPickupModel->updateBatch($update_data, 'id');
      if (!empty($ids_to_delete)) $this->foodPickupModel->delete($ids_to_delete);

      // Menyimpan ke database
      $this->foodPickupModel->db->transCommit();

      // Kirim notifikasi
      if (!empty($studentsToNotify)) {
        $this->sendPushNotifications($studentsToNotify);
      }

      $successMsg = 'Data berhasil disimpan.';

      if ($isJsonRequest) {
        return $this->response->setJSON(['status' => 'success', 'message' => $successMsg]);
      }
      return redirect()->back()->with('success', $successMsg);
    } catch (\Exception $e) {
      $this->foodPickupModel->db->transRollback();
      $errorMsg = 'Terjadi kesalahan: ' . $e->getMessage();

      if ($isJsonRequest) {
        return $this->response->setJSON(['status' => 'error', 'message' => $errorMsg]);
      }
      return redirect()->back()->with('error', $errorMsg);
    }
  }

  public function getStudentAllergens($studentId)
  {
    $allergens = $this->studentAllergenModel
      ->select('allergens.name')
      ->join('allergens', 'allergens.id = student_allergens.allergen_id')
      ->where('student_allergens.student_id', $studentId)
      ->findAll();

    return $this->response->setJSON($allergens);
  }

  private function sendPushNotifications(array $studentIds)
  {
    $dataSiswaDanOrtu = $this->studentModel
      ->select('students.nama_lengkap as nama_siswa, users.id as user_id_ortu')
      ->join('users', 'users.username = students.nis')
      ->whereIn('students.id', $studentIds)
      ->findAll();

    if (empty($dataSiswaDanOrtu)) return;

    $parentNotifications = [];
    foreach ($dataSiswaDanOrtu as $data) {
      if (!empty($data['user_id_ortu'])) {
        $parentNotifications[$data['user_id_ortu']][] = $data['nama_siswa'];
      }
    }

    if (empty($parentNotifications)) return;

    $opensslPath = 'C:\laragon\bin\php\php-8.3.26-Win32-vs16-x64\extras\ssl\openssl.cnf';

    if (!file_exists($opensslPath)) {
      log_message('error', 'CRITICAL: File openssl.cnf TIDAK DITEMUKAN di path: ' . $opensslPath);
      return; // Stop proses
    } else {
      // log_message('info', 'SUCCESS: File openssl.cnf ditemukan.');
    }

    putenv("OPENSSL_CONF=" . $opensslPath);

    // Konfigurasi Web Push
    $auth = [
      'VAPID' => [
        "subject" => "mailto:rizallkadamong@gmail.com",
        "publicKey" => "BNHQu8Oo9mQSFH-oS8NIJlALTkenlIWb0SerlB45_EB88Qj9Sg3EU9lCgtPGcJioJZAOMCJmIxWdwvwtBGib-hE",
        "privateKey" => "driiCUZxFzzvFyi7priistTqEJZRMzMjcK6gRPJ5buo"
      ],
    ];

    try {
      $webPush = new WebPush($auth);
      $subModel = new PushSubscriptionModel();

      // Loop setiap Orang Tua dan kirim ke semua device mereka
      foreach ($parentNotifications as $userIdOrtu => $studentNames) {
        // Ambil subscription devices milik ortu tersebut
        $subscriptions = $subModel->where('user_id', $userIdOrtu)->findAll();

        if (empty($subscriptions)) continue;

        $namesString = implode(', ', $studentNames);

        $payload = json_encode([
          'title' => 'Makanan Telah Diambil',
          'body' => "{$namesString} sudah mengambil makanan hari ini. Klik untuk melihat informasi lebih lanjut!",
          'url' => base_url('food-activity'),
        ]);

        foreach ($subscriptions as $sub) {
          $webPush->queueNotification(
            Subscription::create([
              'endpoint' => $sub['endpoint'],
              'publicKey' => $sub['p256dh'],
              'authToken' => $sub['auth'],
            ]),
            $payload
          );
        }
      }

      // Eksekusi pengiriman
      foreach ($webPush->flush() as $report) {
        // Logging
        // $endpoint = $report->getRequest()->getUri()->__toString();

        // if ($report->isSuccess()) {
        //   // Info sukses (Opsional, kalau mau log penuh)
        //   log_message('info', "[PUSH SUKSES] Dikirim ke: {$endpoint}");
        // } else {
        //   // Info Gagal (PENTING)
        //   log_message('error', "[PUSH GAGAL] Pesan: {$report->getReason()} | Endpoint: {$endpoint}");
        // }
      }
    } catch (\Exception $e) {
      log_message('error', 'Gagal inisialisasi WebPush: ' . $e->getMessage());
    }
  }
}
