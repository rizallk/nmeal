<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodModel extends Model
{
  protected $table = 'foods';
  protected $allowedFields = [
    'nama_menu',
    'bahan_1',
    'bahan_2',
    'bahan_3',
    'bahan_4',
    'bahan_5',
    'bahan_6',
  ];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'nama_menu' => 'required|min_length[3]|max_length[100]',
    'bahan_1_status' => 'permit_empty|in_list[0,1]',
    'bahan_2_status' => 'permit_empty|in_list[0,1]',
    'bahan_3_status' => 'permit_empty|in_list[0,1]',
    'bahan_4_status' => 'permit_empty|in_list[0,1]',
    'bahan_5_status' => 'permit_empty|in_list[0,1]',
    'bahan_6_status' => 'permit_empty|in_list[0,1]',
  ];

  protected $validationMessages = [
    'nama_menu' => [
      'required' => 'Nama menu wajib diisi.',
      'min_length' => 'Nama menu minimal 3 karakter.',
      'max_length' => 'Nama menu maksimal 100 karakter.'
    ],
  ];
}
