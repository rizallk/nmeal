<?php

namespace App\Controllers;

use App\Models\StudentModel;

class DaftarSiswaController extends BaseController
{
    protected $studentModel;
    protected $userRole;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->userRole = session()->get('userRole');
    }

    public function index()
    {
        if ($this->userRole != 'admin') return redirect()->back();

        $search = $this->request->getGet('search') ?? '';
        $kelasFilter = $this->request->getGet('kelas') ?? '';
        $sortColumn = $this->request->getGet('sort-by') ?? 'created_at';
        $sortOrder = $this->request->getGet('sort-order') ?? 'desc';

        $perPage = 10; // default jumlah data yang muncul per-page

        // Dapatkan halaman saat ini dari URL, defaultnya adalah 1
        $currentPage = $this->request->getGet('page') ?? 1;
        $startNumber = ($currentPage - 1) * $perPage; // Logika numbering
        $query = $this->studentModel; // query builder

        if (!empty($kelasFilter)) {
            $query = $query->where('kelas', $kelasFilter);
        }

        // Logika Search
        if (!empty($search)) {
            $query = $query->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('nis', $search)
                ->orLike('kelas', $search)
                ->groupEnd();
        }

        // Logika Sorting
        $validSortColumns = ['nama_lengkap', 'nis', 'kelas', 'created_at'];
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
        ];

        return view('pages/daftar_siswa/tambah', $data);
    }

    public function register()
    {
        $data = [
            'nis' => $this->request->getPost('nis'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'kelas' => $this->request->getPost('kelas'),
        ];


        if (!$this->studentModel->save($data)) {
            return redirect()->back()->withInput()->with('validation', $this->studentModel->errors());
        }


        // Jika registrasi berhasil
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
            // 'validation' => \Config\Services::validation(),
        ];

        return view('pages/daftar_siswa/edit', $data);
    }

    public function update(int $id)
    {
        $siswa = $this->studentModel->find($id);

        if (!$siswa) {
            return redirect()->back()->with('error', 'Siswa tidak ditemukan.');
        }

        $data = [
            'id' => $id,
            'nis' => $this->request->getPost('nis'),
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'kelas' => $this->request->getPost('kelas'),
        ];

        if (!$this->studentModel->save($data)) {
            return redirect()->back()->withInput()->with('validation', $this->studentModel->errors());
        }

        $namaUpdated = $this->request->getPost('nama_lengkap');

        return redirect()->to(site_url('daftar-siswa'))->with('success', 'Siswa "' . esc($namaUpdated) . '" berhasil diupdate.');
    }

    public function delete(int $id)
    {
        // Pastikan method yang digunakan adalah POST dari form
        if ($this->request->getMethod() !== 'POST') {
            return redirect()->back()->with('error', 'Metode penghapusan tidak valid. Harap gunakan tombol Hapus.');
        }

        $siswa = $this->studentModel->find($id);

        if (!$siswa) {
            return redirect()->back()->with('error', 'siswa tidak ditemukan.');
        }

        // Lakukan soft delete
        $this->studentModel->delete($id);

        return redirect()->to(site_url('daftar-siswa'))->with('success', 'Siswa "' . esc($siswa['nama_lengkap']) . '" berhasil dihapus.');
    }
}
