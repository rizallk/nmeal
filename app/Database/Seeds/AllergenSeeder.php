<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AllergenSeeder extends Seeder
{
  public function run()
  {
    $data = [
      [
        'name' => 'Kacang Tanah',
        'created_at' => '2024-01-15 08:30:10',
        'updated_at' => '2024-01-15 08:30:10'
      ],
      [
        'name' => 'Kacang Pohon (Almond, Walnut, Mete, dll.)',
        'created_at' => '2024-01-16 09:00:00',
        'updated_at' => '2024-01-16 09:00:00'
      ],
      [
        'name' => 'Susu Sapi',
        'created_at' => '2024-02-10 10:15:20',
        'updated_at' => '2024-02-10 10:15:20'
      ],
      [
        'name' => 'Telur',
        'created_at' => '2024-02-11 11:05:00',
        'updated_at' => '2024-02-11 11:05:00'
      ],
      [
        'name' => 'Gandum (Gluten)',
        'created_at' => '2024-03-05 14:00:30',
        'updated_at' => '2024-03-05 14:00:30'
      ],
      [
        'name' => 'Kedelai (Soy)',
        'created_at' => '2024-04-20 16:45:00',
        'updated_at' => '2024-04-20 16:45:00'
      ],
      [
        'name' => 'Ikan (Segala Jenis Ikan)',
        'created_at' => '2024-05-01 08:00:00',
        'updated_at' => '2024-05-01 08:00:00'
      ],
      [
        'name' => 'Kerang-kerangan (Udang, Kepiting, Lobster, Cumi)',
        'created_at' => '2024-05-02 09:30:00',
        'updated_at' => '2024-05-02 09:30:00'
      ],
      [
        'name' => 'Wijen',
        'created_at' => '2024-07-10 13:20:15',
        'updated_at' => '2024-07-10 13:20:15'
      ],
      [
        'name' => 'Sulfit',
        'created_at' => '2024-11-20 10:00:00',
        'updated_at' => '2024-11-20 10:00:00'
      ],
    ];

    $this->db->table('allergens')->insertBatch($data);
  }
}
