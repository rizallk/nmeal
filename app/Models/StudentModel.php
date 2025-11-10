<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
  protected $table = 'students';
  protected $allowedFields = [
    'food_id',
    'nama',
    'kelas',
  ];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'food_id' => 'required|is_natural_no_zero',
    'nama' => 'required|min_length[3]|max_length[255]',
    'kelas' => 'required',
  ];

  protected $validationMessages = [
    'nama' => [
      'required' => 'Nama wajib diisi.',
      'min_length' => 'Nama minimal 3 karakter.',
      'max_length' => 'Nama maksimal 255 karakter.'
    ],
    'kelas' => [
      'required' => 'Kelas wajib dipilih.',
    ],
  ];
}
