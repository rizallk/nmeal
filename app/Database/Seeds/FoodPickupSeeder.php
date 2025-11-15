<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FoodPickupSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'student_id'     => 1,
        'user_id'        => 1,
        'status'         => true,
        'laporan'        => 'Tidak makan sampai habis',
        'created_at'     => '2024-05-15 10:00:00',
        'updated_at'     => '2024-05-15 10:00:00',
      ],
      [
        'student_id'     => 2,
        'user_id'        => 1,
        'status'         => false,
        'laporan'        => null,
        'created_at'     => '2024-05-15 10:05:00',
        'updated_at'     => '2024-05-15 10:05:00',
      ],
      [
        'student_id'     => 3,
        'user_id'        => 2,
        'status'         => false,
        'laporan'        => 'Tidak makan sayur',
        'created_at'     => '2024-05-16 10:15:00',
        'updated_at'     => '2024-05-16 10:30:00',
      ],
      [
        'student_id'     => 4,
        'user_id'        => null,
        'status'         => true,
        'laporan'        => 'Habis makan semuanya',
        'created_at'     => '2024-05-16 10:20:00',
        'updated_at'     => '2024-05-16 10:20:00',
      ],
      [
        'student_id'     => 5,
        'user_id'        => 2,
        'status'         => false,
        'laporan'        => null,
        'created_at'     => '2024-05-17 10:00:00',
        'updated_at'     => '2024-05-17 10:00:00',
      ],
      [
        'student_id'     => 6,
        'user_id'        => 1,
        'status'         => true,
        'laporan'        => 'Tidak makan nasi',
        'created_at'     => '2024-05-17 10:05:00',
        'updated_at'     => '2024-05-17 10:05:00',
      ],
      [
        'student_id'     => 7,
        'user_id'        => null,
        'status'         => false,
        'laporan'        => null,
        'created_at'     => '2024-05-18 08:00:00',
        'updated_at'     => '2024-05-18 08:00:00',
      ],
    ];

    $this->db->table('food_pickups')->insertBatch($data);
  }
}
