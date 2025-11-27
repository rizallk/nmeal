<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentFoodSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'student_id' => 1,
        'food_id' => 1,
      ],
      [
        'student_id' => 2,
        'food_id' => 2,
      ],
      [
        'student_id' => 3,
        'food_id' => 2,
      ],
      [
        'student_id' => 4,
        'food_id' => 1,
      ],
      [
        'student_id' => 5,
        'food_id' => 3,
      ],
    ];

    $this->db->table('student_foods')->insertBatch($data);
  }
}
