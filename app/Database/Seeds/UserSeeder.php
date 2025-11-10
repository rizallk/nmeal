<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
  public function run()
  {
    // Buat data dummy
    $data = [
      [
        'nama'       => 'Administrator',
        'role'       => 'admin',
        'username'   => 'admin',
        'password'   => password_hash('123456', PASSWORD_DEFAULT), // Password default: 123456
        'foto'       => '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'nama'       => 'Pak Anto',
        'role'       => 'guru',
        'username'   => 'anto123',
        'password'   => password_hash('123456', PASSWORD_DEFAULT), // Password default: 123456
        'foto'       => '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
      [
        'nama'       => 'Budi',
        'role'       => 'ortu',
        'username'   => 'budi123',
        'password'   => password_hash('123456', PASSWORD_DEFAULT), // Password default: 123456
        'foto'       => '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s'),
      ],
    ];

    // Menggunakan Query Builder untuk insert data batch
    // Ini lebih efisien daripada looping insert
    $this->db->table('users')->insertBatch($data);
  }
}
