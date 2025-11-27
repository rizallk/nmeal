<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'nama_lengkap' => 'Ilham Kurniawan',
        'role'         => 'admin',
        'username'     => 'ilham123',
        'password'     => password_hash('12345', PASSWORD_DEFAULT), // Password default: 123456
        'foto'         => '',
        'created_at'   => date('Y-m-d H:i:s'),
        'updated_at'   => date('Y-m-d H:i:s'),
      ],
      [
        'nama_lengkap' => 'Anto Sutrisno',
        'role'         => 'guru',
        'username'     => 'anto123',
        'password'     => password_hash('12345', PASSWORD_DEFAULT), // Password default: 123456
        'foto'         => '',
        'created_at'   => date('Y-m-d H:i:s'),
        'updated_at'   => date('Y-m-d H:i:s'),
      ],
      [
        'nama_lengkap' => 'Budi Santoso',
        'role'         => 'ortu',
        'username'     => '0051234567',
        'password'     => password_hash('12345', PASSWORD_DEFAULT), // Password default: 123456
        'foto'         => '',
        'created_at'   => date('Y-m-d H:i:s'),
        'updated_at'   => date('Y-m-d H:i:s'),
      ],
    ];

    $this->db->table('users')->insertBatch($data);
  }
}
