<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FoodPickupSeeder extends Seeder
{
  public function run()
  {
    $now = date('Y-m-d H:i:s');

    $data = [
      [
        'student_id'     => 1,
        'user_id'        => 1,
        'food_id'        => 1,
        'status'         => true,
        'catatan'        => 'Tidak makan sampai habis',
        'created_at'     => $now,
        'updated_at'     => $now,
      ],
      [
        'student_id'     => 2,
        'user_id'        => 1,
        'food_id'        => 2,
        'status'         => false,
        'catatan'        => null,
        'created_at'     => $now,
        'updated_at'     => $now,
      ],
      [
        'student_id'     => 3,
        'user_id'        => 1,
        'food_id'        => 2,
        'status'         => false,
        'catatan'        => 'Tidak makan sayur',
        'created_at'     => $now,
        'updated_at'     => $now,
      ],
      [
        'student_id'     => 4,
        'user_id'        => 1,
        'food_id'        => 3,
        'status'         => true,
        'catatan'        => 'Habis makan semuanya',
        'created_at'     => $now,
        'updated_at'     => $now,
      ],
      [
        'student_id'     => null,
        'user_id'        => null,
        'food_id'        => null,
        'status'         => false,
        'catatan'        => null,
        'created_at'     => $now,
        'updated_at'     => $now,
      ],
      [
        'student_id'     => 6,
        'user_id'        => 1,
        'food_id'        => 5,
        'status'         => true,
        'catatan'        => 'Tidak makan nasi',
        'created_at'     => $now,
        'updated_at'     => $now,
      ],
      [
        'student_id'     => null,
        'user_id'        => null,
        'food_id'        => null,
        'status'         => false,
        'catatan'        => null,
        'created_at'     => $now,
        'updated_at'     => $now,
      ],
    ];

    $this->db->table('food_pickups')->insertBatch($data);
  }
}
