<?php

namespace App\Controllers;

use App\Models\AllergenModel;
use App\Models\FoodAllergenModel;
use App\Models\FoodIngredientModel;
use App\Models\FoodModel;
use App\Models\IngredientModel;
use App\Models\UserModel;

class DaftarMakananController extends BaseController
{
  protected $foodModel;
  protected $allergenModel;
  protected $foodAllergenModel;
  protected $ingredientModel;
  protected $foodIngredientModel;
  protected $userModel;
  protected $userRole;

  public function __construct()
  {
    $this->foodModel = new FoodModel();
    $this->userModel = new UserModel();
    $this->allergenModel = new AllergenModel();
    $this->foodAllergenModel = new FoodAllergenModel();
    $this->ingredientModel = new IngredientModel();
    $this->foodIngredientModel = new FoodIngredientModel();
    $this->userRole = session()->get('userRole');
  }

  public function index()
  {
    if ($this->userRole == 'ortu') return redirect()->back();

    $search = $this->request->getGet('search') ?? '';
    $sortColumn = $this->request->getGet('sort-by') ?? 'name';
    $sortOrder = $this->request->getGet('sort-order') ?? 'asc';

    $perPage = 20; // default jumlah data yang muncul per-page

    $currentPage = $this->request->getGet('page') ?? 1;
    $startNumber = ($currentPage - 1) * $perPage; // Logika numbering
    $query = $this->foodModel
      ->select('
        foods.*, 
        GROUP_CONCAT(DISTINCT allergens.name SEPARATOR ", ") as allergens, 
        GROUP_CONCAT(DISTINCT ingredients.name SEPARATOR ", ") as ingredients
    ')
      ->join('food_allergens', 'food_allergens.food_id = foods.id', 'left')
      ->join('allergens', 'allergens.id = food_allergens.allergen_id', 'left')
      ->join('food_ingredients', 'food_ingredients.food_id = foods.id', 'left')
      ->join('ingredients', 'ingredients.id = food_ingredients.ingredient_id', 'left')
      ->groupBy('foods.id');

    // Logika Search
    if (!empty($search)) {
      $query = $query->groupStart()
        ->like('foods.name', $search)
        ->orLike('ingredients.name', $search)
        ->orLike('allergens.name', $search)
        ->groupEnd();
    }

    // Logika Sorting
    $validSortColumns = ['name', 'bahan_makanan'];
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
      'pageTitle' => 'Daftar Makanan',
      'daftarMakanan' => $query->paginate($perPage, 'default', $currentPage),
      'pager' => $this->foodModel->pager,
      'search' => $search,
      'sortColumn' => $sortColumn,
      'sortOrder' => $sortOrder,
      'startNumber' => $startNumber,
      'currentFilters' => $currentFilters
    ];

    $data['pager']->setPath('daftar-makanan', 'default');

    return view('pages/daftar_makanan/index', $data);
  }

  public function registerView()
  {
    if ($this->userRole == 'ortu') return redirect()->back();

    $data = [
      'pageTitle' => 'Tambah Makanan',
      'allergens' => $this->allergenModel->select('id, name')->findAll(),
      'ingredients' => $this->ingredientModel->select('id, name')->findAll()
    ];

    return view('pages/daftar_makanan/tambah', $data);
  }

  public function register()
  {
    if ($this->userRole == 'ortu') return redirect()->back();
    $db = \Config\Database::connect();
    $db->transBegin();

    $dataFood = [
      'name' => $this->request->getPost('name'),
    ];

    if (!$this->foodModel->save($dataFood)) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('validation', $this->foodModel->errors());
    }

    $foodId = $this->foodModel->getInsertID();

    $allergens = $this->request->getPost('allergens');

    if (!empty($allergens) && is_array($allergens)) {
      $dataAllergenBatch = [];
      foreach ($allergens as $allergenId) {
        $dataAllergenBatch[] = [
          'food_id'     => $foodId,
          'allergen_id' => $allergenId
        ];
      }
      if (!empty($dataAllergenBatch)) {
        $this->foodAllergenModel->insertBatch($dataAllergenBatch);
      }
    }

    $ingredients = $this->request->getPost('ingredients');

    if (!empty($ingredients) && is_array($ingredients)) {
      $dataIngredientBatch = [];
      foreach ($ingredients as $ingredientId) {
        $dataIngredientBatch[] = [
          'food_id'       => $foodId,
          'ingredient_id' => $ingredientId
        ];
      }
      if (!empty($dataIngredientBatch)) {
        $this->foodIngredientModel->insertBatch($dataIngredientBatch);
      }
    }

    if ($db->transStatus() === false) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data makanan. Terjadi masalah database.');
    }

    $db->transCommit();

    return redirect()->to('/tambah-makanan')->with('success', 'Tambah makanan "' . $this->request->getPost('name') . '" berhasil!');
  }

  public function edit(int $id)
  {
    if ($this->userRole == 'ortu') return redirect()->back();
    $makanan = $this->foodModel->find($id);

    if (!$makanan) {
      return redirect()->back()->with('error', 'Nama makanan tidak ditemukan.');
    }

    $data = [
      'pageTitle' => 'Edit makanan - ' . $makanan['name'],
      'makanan'  => $makanan,
      'allergens' => $this->allergenModel->select('id, name')->findAll(),
      'foodAllergens' => $this->foodAllergenModel->where('food_id', $id)->findAll(),
      'ingredients' => $this->ingredientModel->select('id, name')->findAll(),
      'foodIngredients' => $this->foodIngredientModel->where('food_id', $id)->findAll()
    ];

    return view('pages/daftar_makanan/edit', $data);
  }

  public function update(int $id)
  {
    if ($this->userRole == 'ortu') return redirect()->back();
    $makanan = $this->foodModel->find($id);

    if (!$makanan) {
      return redirect()->back()->with('error', 'Nama Makanan tidak ditemukan.');
    }

    $db = \Config\Database::connect(); // Instance database
    $db->transBegin();

    // Data Food
    $dataFood = [
      'id' => $id,
      'name' => $this->request->getPost('name'),
    ];

    if (!$this->foodModel->save($dataFood)) {
      $db->transRollback();
      return redirect()->back()->withInput()->with('validation', $this->foodModel->errors());
    }

    // Data alergen makanan
    $this->foodAllergenModel->where('food_id', $id)->delete();
    $allergens = $this->request->getPost('allergens');

    if ($allergens && is_array($allergens)) {
      $dataAllergenBatch = [];
      foreach ($allergens as $allergenId) {
        $dataAllergenBatch[] = [
          'food_id'  => $id,
          'allergen_id' => $allergenId
        ];
      }

      if (!empty($dataAllergenBatch)) {
        $this->foodAllergenModel->insertBatch($dataAllergenBatch);
      }
    }

    $this->foodIngredientModel->where('food_id', $id)->delete();
    $ingredients = $this->request->getPost('ingredients');

    if ($ingredients && is_array($ingredients)) {
      $dataIngredientBatch = [];
      foreach ($ingredients as $ingredientId) {
        $dataIngredientBatch[] = [
          'food_id'  => $id,
          'ingredient_id' => $ingredientId
        ];
      }

      if (!empty($dataIngredientBatch)) {
        $this->foodIngredientModel->insertBatch($dataIngredientBatch);
      }
    }

    // ===============================

    if ($db->transStatus() === false) {
      $db->transRollback();
      return redirect()->back()->with('error', 'Gagal mengubah data makanan. Terjadi masalah saat menyimpan data.');
    }

    // Komit (Simpan Permanen) jika semua berhasil
    $db->transCommit();

    $namaUpdated = $this->request->getPost('name');

    return redirect()->back()->with('success', 'Makanan "' . esc($namaUpdated) . '" berhasil diupdate.');
  }

  public function delete(int $id)
  {
    if ($this->userRole == 'ortu') return redirect()->back();
    if ($this->request->getMethod() !== 'POST') {
      return redirect()->back()->with('error', 'Metode penghapusan tidak valid. Harap gunakan tombol Hapus.');
    }

    $db = \Config\Database::connect(); // Instance database
    $db->transBegin();

    $makanan = $this->foodModel->find($id);

    if (!$makanan) {
      return redirect()->back()->with('error', 'makanan tidak ditemukan.');
    }

    $this->foodModel->delete($id);

    if ($db->transStatus() === false) {
      $db->transRollback();
      return redirect()->back()->with('error', 'Gagal mengubah data makanan. Terjadi masalah saat menyimpan data.');
    }

    // Komit (Simpan Permanen) jika semua berhasil
    $db->transCommit();

    return redirect()->back()->with('success', 'Makanan "' . esc($makanan['name']) . '" berhasil dihapus.');
  }
}
