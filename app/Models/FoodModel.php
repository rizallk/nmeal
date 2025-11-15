<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodModel extends Model
{
  protected $table = 'foods';
  protected $allowedFields = [
    'name',
  ];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'name' => 'required|min_length[3]|max_length[100]',
  ];

  protected $validationMessages = [
    'name' => [
      'required' => 'Nama menu wajib diisi.',
      'min_length' => 'Nama menu minimal 3 karakter.',
      'max_length' => 'Nama menu maksimal 100 karakter.'
    ],
  ];
}
