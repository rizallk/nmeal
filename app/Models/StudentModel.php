<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentModel extends Model
{
  protected $table = 'students';
  protected $allowedFields = [
    'nis',
    'nama_lengkap',
    'kelas',
  ];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'nis' => 'required|min_length[3]|max_length[50]',
    'nama_lengkap' => 'required|min_length[3]|max_length[255]',
    'kelas' => 'required',
  ];

  protected $validationMessages = [
    'nis' => [
      'required' => 'NIS wajib diisi.',
      'min_length' => 'NIS minimal 3 karakter.',
      'max_length' => 'NIS maksimal 50 karakter.'
    ],
    'nama_lengkap' => [
      'required' => 'Nama wajib diisi.',
      'min_length' => 'Nama minimal 3 karakter.',
      'max_length' => 'Nama maksimal 255 karakter.'
    ],
    'kelas' => [
      'required' => 'Kelas wajib dipilih.',
    ],
  ];
}
