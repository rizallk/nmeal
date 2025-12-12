<?php

namespace App\Controllers;

use App\Libraries\GeminiAI;
use App\Models\AllergenModel;
use App\Models\FoodPickupModel;
use App\Models\StudentAllergenModel;
use App\Models\StudentModel;

class DashboardController extends BaseController
{
    protected $userRole;
    protected $studentId;
    protected $foodPickupModel;
    protected $studentModel;
    protected $studentAllergenModel;
    protected $allergenModel;

    public function __construct()
    {
        $this->userRole = session()->get('userRole');
        $this->studentId = session()->get('studentId');
        $this->foodPickupModel = new FoodPickupModel();
        $this->studentModel = new StudentModel();
        $this->studentAllergenModel = new StudentAllergenModel();
        $this->allergenModel = new AllergenModel();
    }

    public function index()
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];

        if ($this->userRole == 'admin' || $this->userRole == 'guru') {
            // $weeklyData = [];
            $monthlyData = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $monthlyData[$date] = 0;
            }

            $startDate = date('Y-m-d', strtotime('-29 days'));
            $endDate   = date('Y-m-d');

            $queryResult = $this->foodPickupModel
                ->select('DATE(created_at) as tgl, COUNT(*) as total')
                ->where('DATE(created_at) >=', $startDate)
                ->where('DATE(created_at) <=', $endDate)
                ->groupBy('DATE(created_at)')
                ->orderBy('tgl', 'ASC')
                ->findAll();

            foreach ($queryResult as $row) {
                if (isset($monthlyData[$row['tgl']])) {
                    $monthlyData[$row['tgl']] = (int) $row['total'];
                }
            }

            $chartLabels = [];
            $chartValues = [];

            $bulanIndo = [
                '01' => 'Jan',
                '02' => 'Feb',
                '03' => 'Mar',
                '04' => 'Apr',
                '05' => 'Mei',
                '06' => 'Jun',
                '07' => 'Jul',
                '08' => 'Agu',
                '09' => 'Sep',
                '10' => 'Okt',
                '11' => 'Nov',
                '12' => 'Des'
            ];

            foreach ($monthlyData as $date => $total) {
                $time = strtotime($date);
                $tgl = date('d', $time);
                $bln = date('m', $time);
                $chartLabels[] = $tgl . ' ' . $bulanIndo[$bln];
                $chartValues[] = $total;
            }

            $data['classChart'] = [
                'labels' => json_encode($chartLabels),
                'data'   => json_encode($chartValues)
            ];

            $studentAllergens = $this->studentAllergenModel
                ->select('allergens.name as allergen_name, COUNT(*) as total_students')
                ->join('allergens', 'allergens.id = student_allergens.allergen_id')
                ->groupBy('allergen_id')
                ->orderBy('total_students', 'DESC')
                ->findAll();

            $allergenLabels = [];
            $allergenValues = [];

            foreach ($studentAllergens as $row) {
                $allergenLabels[] = $row['allergen_name'];
                $allergenValues[] = (int) $row['total_students'];
            }

            $data['studentAllergenChart'] = [
                'labels' => json_encode($allergenLabels),
                'data'   => json_encode($allergenValues)
            ];

            $today = date('Y-m-d');
            $data['totalSudahMakan'] = $this->foodPickupModel->where('DATE(created_at)', $today)->countAllResults();
            $totalSiswa = $this->studentModel->countAllResults();
            $data['totalBelumMakan'] = $totalSiswa - $data['totalSudahMakan'];

            return view('pages/dashboard/admin', $data);
        } else if ($this->userRole == 'ortu') {
            $pickup = $this->foodPickupModel
                ->where('student_id', $this->studentId)
                ->where('DATE(created_at)', date("Y-m-d"))
                ->first();

            $data['catatan'] = $pickup['catatan'] ?? '';
            $data['status'] = $pickup['status'] ?? 0;

            return view('pages/dashboard/ortu', $data);
        }
    }

    public function getRecommendation()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $studentNIS = session()->get('username'); // NIS Siswa
        if (!$studentNIS || $this->studentId == null) {
            return $this->response->setJSON(['error' => 'Data siswa tidak ditemukan.']);
        }

        $today = date('Y-m-d');
        $pickup = $this->foodPickupModel
            ->where('student_id', $this->studentId)
            ->where('DATE(created_at)', $today)
            ->first();

        $catatan = $pickup['catatan'] ?? '';
        $status = $pickup['status'] ?? 0;

        if ($status == 0) {
            // Kasus: Belum mengambil makan
            $prompt = "Anak saya hari ini belum mengambil makan siang di sekolah. " .
                "Berikan saya 3 ide menu makanan yang praktis, bergizi seimbang, dan menggugah selera untuk anak SD. Buat dalam kalimat yang singkat, padat dan jelas.";
        } elseif (!empty($catatan)) {
            // Kasus: Ada catatan khusus
            $prompt = "Saya butuh saran. Anak saya hari ini makan di sekolah tapi ada catatan dari guru: '$catatan'. " .
                "Berdasarkan catatan itu, tolong berikan 3 rekomendasi menu makanan untuk melengkapi nutrisinya atau mengatasi masalah tersebut. " .
                "Buat dalam kalimat yang singkat, padat dan jelas.";
        } else {
            // Kasus: Sudah makan dan aman (tidak ada catatan)
            $prompt = "Anak saya hari ini makan lahap di sekolah tanpa masalah. " .
                "Berikan saya 1 fakta unik singkat tentang gizi anak SD dan 1 ide camilan sehat. Buat dalam kalimat yang singkat, padat dan jelas.";
        }

        // Panggil API Gemini
        try {
            $gemini = new GeminiAI();
            $result = $gemini->generateText($prompt);

            $formattedResult = preg_replace('/\*\*(.*?)\*\*/', '<strong class="text-success">$1</strong>', $result);
            $formattedResult = preg_replace('/^\* /m', '• ', $formattedResult);
            $formattedResult = preg_replace('/(\d+\.)/', '<br>$1', $formattedResult);
            $formattedResult = nl2br($formattedResult);
            $formattedResult = ltrim($formattedResult, '<br>');

            return $this->response->setJSON(['success' => true, 'message' => $formattedResult]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal : ' . $e->getMessage()]);
        }
    }
}
