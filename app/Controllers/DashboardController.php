<?php

namespace App\Controllers;

use App\Libraries\GeminiAI;
use App\Models\FoodPickupModel;
use App\Models\StudentModel;

class DashboardController extends BaseController
{
    protected $userRole;
    protected $foodPickupModel;
    protected $studentModel;

    public function __construct()
    {
        $this->userRole = session()->get('userRole');
        $this->foodPickupModel = new FoodPickupModel();
        $this->studentModel = new StudentModel();
    }

    public function index(): string
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];

        if ($this->userRole == 'admin' || $this->userRole == 'guru') {
            return view('pages/dashboard/admin', $data);
        } else if ($this->userRole == 'ortu') {
            return view('pages/dashboard/ortu', $data);
        }

        return '';
    }

    public function getRecommendation()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        // 1. Ambil ID Anak (Sesuaikan dengan logika project Anda)
        // Contoh: Jika orang tua login, kita ambil ID anaknya dari session atau database
        $studentId = session()->get('studentId');

        // Jika logic session studentId belum ada, kita hardcode dulu untuk testing:
        // $studentId = 1; 

        if (!$studentId) {
            return $this->response->setJSON(['error' => 'Data siswa tidak ditemukan.']);
        }

        // 2. Cek Catatan Makan Hari Ini
        $today = date('Y-m-d');
        $pickup = $this->foodPickupModel
            ->where('student_id', $studentId)
            ->where('DATE(created_at)', $today)
            ->first();

        // 3. Susun Prompt untuk Gemini
        $note = $pickup['catatan'] ?? '';
        $status = $pickup['status'] ?? 0;

        if ($status == 0) {
            // Kasus: Belum mengambil makan
            $prompt = "Halo Gemini, anak saya hari ini belum mengambil makan siang di sekolah. " .
                "Berikan saya 3 ide menu makanan yang praktis, bergizi seimbang, dan menggugah selera untuk anak SD.";
        } elseif (!empty($note)) {
            // Kasus: Ada catatan khusus (misal: Gak suka sayur, Alergi, dll)
            $prompt = "Halo Gemini, saya butuh saran. Anak saya hari ini makan di sekolah tapi ada catatan dari guru: '$note'. " .
                "Berdasarkan catatan itu, tolong berikan 3 rekomendasi menu makan malam ini untuk melengkapi nutrisinya atau mengatasi masalah tersebut. " .
                "Jawab dengan ramah dan singkat.";
        } else {
            // Kasus: Sudah makan dan aman (tidak ada catatan)
            $prompt = "Anak saya hari ini makan lahap di sekolah tanpa masalah. " .
                "Berikan saya 1 fakta unik singkat tentang gizi anak SD dan 1 ide camilan sehat.";
        }

        // 4. Panggil API Gemini
        try {
            $gemini = new GeminiAI();
            $result = $gemini->generateText($prompt);

            // Ubah formatting markdown basic ke HTML (optional, untuk bold)
            $formattedResult = str_replace('**', '<b>', $result);
            $formattedResult = str_replace('**', '</b>', $formattedResult);
            $formattedResult = nl2br($formattedResult); // Ubah enter jadi <br>

            return $this->response->setJSON(['success' => true, 'message' => $formattedResult]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghubungi AI: ' . $e->getMessage()]);
        }
    }
}
