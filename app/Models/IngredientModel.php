<?php

namespace App\Models;

use CodeIgniter\Model;

class IngredientModel extends Model
{
  protected $table = 'ingredients';
  protected $allowedFields = [
    'name',
  ];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'name' => 'required|min_length[3]|max_length[100]|is_unique[ingredients.name]',
  ];

  protected $validationMessages = [
    'name' => [
      'required' => 'Nama bahan makanan wajib diisi.',
      'is_unique' => 'Nama bahan makanan sudah ada.',
      'min_length' => 'Nama bahan makanan minimal 3 karakter.',
      'max_length' => 'Nama bahan makanan maksimal 100 karakter.'
    ],
  ];
}
