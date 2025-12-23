<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
  protected $table = 'users';
  protected $allowedFields = ['nama_lengkap', 'role', 'username', 'password', 'foto'];
  protected $useTimestamps = true;
  protected $dateFormat = 'datetime';
  protected $createdField = 'created_at';
  protected $updatedField = 'updated_at';

  protected $validationRules = [
    'nama_lengkap' => 'required|min_length[3]|max_length[50]',
    'role' => 'required',
    'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
    'password' => 'required|min_length[4]|max_length[50]',
    'foto' => 'permit_empty|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png]|max_size[foto,2048]',
  ];

  protected $validationMessages = [
    'nama_lengkap' => [
      'required' => 'Nama lengkap wajib diisi.',
      'min_length' => 'Nama lengkap minimal 3 karakter.',
      'max_length' => 'Nama lengkap maksimal 50 karakter.'
    ],
    'role' => [
      'required' => 'Role wajib dipilih.',
    ],
    'username' => [
      'required' => 'Username wajib diisi.',
      'is_unique' => 'Username sudah terdaftar.',
      'min_length' => 'Username minimal 3 karakter.',
      'max_length' => 'Username maksimal 50 karakter.'
    ],
    'password' => [
      'required' => 'Password wajib diisi',
      'min_length' => 'Password minimal 4 karakter.',
      'max_length' => 'Password maksimal 50 karakter.'
    ],
    'foto' => [
      'is_image' => 'File yang diupload harus berupa gambar.',
      'mime_in'  => 'Format gambar harus .jpg, .jpeg, atau .png.',
      'max_size' => 'Ukuran gambar maksimal 2MB.',
    ]
  ];

  protected $beforeInsert = ['hashPassword'];
  protected $beforeUpdate = ['hashPassword'];

  protected function hashPassword(array $data)
  {
    if (! isset($data['data']['password']) || empty($data['data']['password'])) {
      // Jika password kosong saat update, jangan lakukan apa-apa
      if (isset($data['data']['password'])) {
        unset($data['data']['password']);
      }
      return $data;
    }

    // Hash password menggunakan PASSWORD_DEFAULT
    $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

    return $data;
  }
}
