<?php

namespace App\Controllers;

use App\Libraries\GeminiAI;
use App\Models\FoodPickupModel;

class DashboardController extends BaseController
{
    protected $userRole;
    protected $foodPickupModel;
    protected $studentId;

    public function __construct()
    {
        $this->userRole = session()->get('userRole');
        $this->studentId = session()->get('studentId');
        $this->foodPickupModel = new FoodPickupModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];

        if ($this->userRole == 'admin' || $this->userRole == 'guru') {
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

        return '';
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
