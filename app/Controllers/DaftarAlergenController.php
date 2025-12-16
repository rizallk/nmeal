<?php

namespace App\Controllers;

use App\Models\AllergenModel;
use App\Models\UserModel;

class DaftarAlergenController extends BaseController
{
  protected $allergenModel;
  protected $userModel;
  protected $userRole;

  public function __construct()
  {
    $this->userModel = new UserModel();
    $this->allergenModel = new AllergenModel();
    $this->userRole = session()->get('userRole');
  }

  public function index()
  {
    if ($this->userRole != 'admin') return redirect()->back();

    $search = $this->request->getGet('search') ?? '';
    $sortColumn = $this->request->getGet('sort-by') ?? 'name';
    $sortOrder = $this->request->getGet('sort-order') ?? 'asc';

    $perPage = 20; // default jumlah data yang muncul per-page

    $currentPage = $this->request->getGet('page') ?? 1;
    $startNumber = ($currentPage - 1) * $perPage; // Logika numbering
    $query = $this->allergenModel;

    // Logika Search
    if (!empty($search)) {
      $query = $query->groupStart()
        ->like('name', $search)
        ->groupEnd();
    }

    // Logika Sorting
    $validSortColumns = ['name'];
    if (in_array($sortColumn, $validSortColumns)) {
      $query = $query->orderBy($sortColumn, $sortOrder);
    }

    $currentFilters = [
      'search' => $search,
      'sort-by' => $sortColumn,
      'sort-order' => $sortOrder
    ];
    $currentFilters = array_filter($currentFilters);

    $data = [
      'pageTitle' => 'Daftar Alergen',
      'daftarAlergen' => $query->paginate($perPage, 'default', $currentPage),
      'pager' => $this->allergenModel->pager,
      'search' => $search,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'startNumber' => $startNumber,
      'currentFilters' => $currentFilters
    ];

    $data['pager']->setPath('daftar-alergen', 'default');

    return view('pages/daftar_alergen/index', $data);
  }

  public function registerView()
  {
    if ($this->userRole !== 'admin') return redirect()->back();

    $data = [
      'pageTitle' => 'Tambah Alergen',
    ];

    return view('pages/daftar_alergen/tambah', $data);
  }

  public function register()
  {

    $dataFood = [
      'name' => $this->request->getPost('name'),
    ];

    if (!$this->allergenModel->save($dataFood)) {
      return redirect()->back()->withInput()->with('validation', $this->allergenModel->errors());
    }

    return redirect()->to('/tambah-alergen')->with('success', 'Tambah alergen "' . $this->request->getPost('name') . '" berhasil!');
  }

  public function edit(int $id)
  {
    $allergen  = $this->allergenModel->find($id);

    if (!$allergen) {
      return redirect()->back()->with('error', 'Nama alergen tidak ditemukan.');
    }

    $data = [
      'pageTitle' => 'Edit Alergen - ' . $allergen['name'],
      'allergen'  => $allergen
    ];

    return view('pages/daftar_alergen/edit', $data);
  }

  public function update(int $id)
  {
    $allergen = $this->allergenModel->find($id);

    if (!$allergen) {
      return redirect()->back()->with('error', 'Nama alergen tidak ditemukan.');
    }


    // Data Food
    $dataFood = [
      'id' => $id,
      'name' => $this->request->getPost('name'),
    ];

    if (!$this->allergenModel->save($dataFood)) {
      return redirect()->back()->withInput()->with('validation', $this->allergenModel->errors());
    }

    $namaUpdated = $this->request->getPost('name');

    return redirect()->back()->with('success', 'Alergen "' . esc($namaUpdated) . '" berhasil diupdate.');
  }

  public function delete(int $id)
  {
    if ($this->request->getMethod() !== 'POST') {
      return redirect()->back()->with('error', 'Metode penghapusan tidak valid. Harap gunakan tombol Hapus.');
    }


    $allergen = $this->allergenModel->find($id);

    if (!$allergen) {
      return redirect()->back()->with('error', 'alergen tidak ditemukan.');
    }

    $this->allergenModel->delete($id);

    return redirect()->back()->with('success', 'Alergen "' . esc($allergen['name']) . '" berhasil dihapus.');
  }
}
