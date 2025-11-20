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
        'catatan'        => 'Tidak makan sampai habis',
        'created_at'     => '2025-11-12 10:00:00',
        'updated_at'     => '2025-11-12 10:00:00',
      ],
      [
        'student_id'     => 2,
        'user_id'        => 1,
        'status'         => false,
        'catatan'        => null,
        'created_at'     => '2025-11-12 10:11:00',
        'updated_at'     => '2025-11-12 10:11:00',
      ],
      [
        'student_id'     => 3,
        'user_id'        => 2,
        'status'         => false,
        'catatan'        => 'Tidak makan sayur',
        'created_at'     => '2025-11-13 10:15:00',
        'updated_at'     => '2025-11-13 10:30:00',
      ],
      [
        'student_id'     => 4,
        'user_id'        => null,
        'status'         => true,
        'catatan'        => 'Habis makan semuanya',
        'created_at'     => '2025-11-13 10:20:00',
        'updated_at'     => '2025-11-13 10:20:00',
      ],
      [
        'student_id'     => 5,
        'user_id'        => 2,
        'status'         => false,
        'catatan'        => null,
        'created_at'     => '2025-11-14 10:00:00',
        'updated_at'     => '2025-11-14 10:00:00',
      ],
      [
        'student_id'     => 6,
        'user_id'        => 1,
        'status'         => true,
        'catatan'        => 'Tidak makan nasi',
        'created_at'     => '2025-11-14 10:11:00',
        'updated_at'     => '2025-11-14 10:11:00',
      ],
      [
        'student_id'     => 7,
        'user_id'        => null,
        'status'         => false,
        'catatan'        => null,
        'created_at'     => '2025-11-15 08:00:00',
        'updated_at'     => '2025-11-15 08:00:00',
      ],
    ];

    $this->db->table('food_pickups')->insertBatch($data);
  }
}
