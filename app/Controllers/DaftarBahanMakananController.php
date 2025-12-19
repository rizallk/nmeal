<?php

namespace App\Controllers;

use App\Models\IngredientModel;

class DaftarBahanMakananController extends BaseController
{
  protected $ingredientModel;
  protected $userRole;

  public function __construct()
  {
    $this->ingredientModel = new IngredientModel();
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
    $query = $this->ingredientModel;

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
      'pageTitle' => 'Daftar Bahan Makanan',
      'daftarBahanMakanan' => $query->paginate($perPage, 'default', $currentPage),
      'pager' => $this->ingredientModel->pager,
      'search' => $search,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'startNumber' => $startNumber,
      'currentFilters' => $currentFilters
    ];

    $data['pager']->setPath('daftar-bahan-makanan', 'default');

    return view('pages/daftar_bahan_makanan/index', $data);
  }

  public function registerView()
  {
    if ($this->userRole !== 'admin') return redirect()->back();

    $data = [
      'pageTitle' => 'Tambah Bahan Makanan',
    ];

    return view('pages/daftar_bahan_makanan/tambah', $data);
  }

  public function register()
  {

    $dataFood = [
      'name' => $this->request->getPost('name'),
    ];

    if (!$this->ingredientModel->save($dataFood)) {
      return redirect()->back()->withInput()->with('validation', $this->ingredientModel->errors());
    }

    return redirect()->to('/tambah-bahan-makanan')->with('success', 'Tambah bahan makanan "' . $this->request->getPost('name') . '" berhasil!');
  }

  public function edit(int $id)
  {
    $ingredient  = $this->ingredientModel->find($id);

    if (!$ingredient) {
      return redirect()->back()->with('error', 'Nama bahan makanan tidak ditemukan.');
    }

    $data = [
      'pageTitle' => 'Edit Bahan Makanan - ' . $ingredient['name'],
      'ingredient'  => $ingredient
    ];

    return view('pages/daftar_bahan_makanan/edit', $data);
  }

  public function update(int $id)
  {
    $ingredient = $this->ingredientModel->find($id);

    if (!$ingredient) {
      return redirect()->back()->with('error', 'Nama bahan makanan tidak ditemukan.');
    }


    // Data Food
    $dataFood = [
      'id' => $id,
      'name' => $this->request->getPost('name'),
    ];

    if (!$this->ingredientModel->save($dataFood)) {
      return redirect()->back()->withInput()->with('validation', $this->ingredientModel->errors());
    }

    $namaUpdated = $this->request->getPost('name');

    return redirect()->back()->with('success', 'Bahan makanan "' . esc($namaUpdated) . '" berhasil diupdate.');
  }

  public function delete(int $id)
  {
    if ($this->request->getMethod() !== 'POST') {
      return redirect()->back()->with('error', 'Metode penghapusan tidak valid. Harap gunakan tombol Hapus.');
    }


    $ingredient = $this->ingredientModel->find($id);

    if (!$ingredient) {
      return redirect()->back()->with('error', 'Bahan makanan tidak ditemukan.');
    }

    $this->ingredientModel->delete($id);

    return redirect()->back()->with('success', 'Bahan makanan "' . esc($ingredient['name']) . '" berhasil dihapus.');
  }
}
