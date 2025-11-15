<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FoodSeeder extends Seeder
{
  public function run()
  {
    $data = [
      // --- Kumpulan Menu Nasi, Lauk Utama ---
      [
        'name' => 'Nasi, Ayam Goreng Lengkuas, Tumis Buncis Wortel',
        'created_at' => '2024-08-01 09:00:00',
        'updated_at' => '2024-08-01 09:00:00'
      ],
      [
        'name' => 'Nasi, Ikan Kembung Bakar, Sayur Bayam Bening',
        'created_at' => '2024-08-02 09:00:00',
        'updated_at' => '2024-08-02 09:00:00'
      ],
      [
        'name' => 'Nasi, Semur Daging Sapi Kentang, Tumis Tauge',
        'created_at' => '2024-08-03 09:00:00',
        'updated_at' => '2024-08-03 09:00:00'
      ],
      [
        'name' => 'Nasi, Lele Goreng, Tumis Kangkung Bawang Putih',
        'created_at' => '2024-08-04 09:00:00',
        'updated_at' => '2024-08-04 09:00:00'
      ],
      [
        'name' => 'Nasi, Telur Dadar Sayur, Sup Wortel Kentang Buncis',
        'created_at' => '2024-08-05 09:00:00',
        'updated_at' => '2024-08-05 09:00:00'
      ],
      [
        'name' => 'Nasi, Tahu Tempe Bacem, Sayur Lodeh Labu Siam',
        'created_at' => '2024-08-06 09:00:00',
        'updated_at' => '2024-08-06 09:00:00'
      ],
      [
        'name' => 'Nasi, Ayam Kecap Manis, Capcay Sederhana (Wortel, Kol)',
        'created_at' => '2024-08-07 09:00:00',
        'updated_at' => '2024-08-07 09:00:00'
      ],
      [
        'name' => 'Nasi, Ikan Tongkol Suwir Pedas Manis, Tumis Kol Telur',
        'created_at' => '2024-08-08 09:00:00',
        'updated_at' => '2024-08-08 09:00:00'
      ],
      [
        'name' => 'Nasi, Perkedel Kentang Daging Cincang, Sup Bayam Jagung',
        'created_at' => '2024-08-09 09:00:00',
        'updated_at' => '2024-08-09 09:00:00'
      ],
      [
        'name' => 'Nasi, Rolade Ayam Saus Tomat, Setup Wortel Buncis',
        'created_at' => '2024-08-10 09:00:00',
        'updated_at' => '2024-08-10 09:00:00'
      ],
    ];

    // Masukkan data secara batch
    $this->db->table('foods')->insertBatch($data);
  }
}
