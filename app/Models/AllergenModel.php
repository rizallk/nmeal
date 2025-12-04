<?php

namespace App\Models;

use CodeIgniter\Model;

class AllergenModel extends Model
{
  protected $table = 'allergens';
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
      'required' => 'Nama alergi wajib diisi.',
      'min_length' => 'Nama alergi minimal 3 karakter.',
      'max_length' => 'Nama alergi maksimal 100 karakter.'
    ],
  ];
}
