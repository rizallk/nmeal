<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentSeeder extends Seeder
{
  /**
   * Menjalankan proses seeding untuk tabel 'students'.
   *
   * Perintah CLI untuk menjalankan seeder ini:
   * php spark db:seed StudentSeeder
   */
  public function run()
  {
    // Data dummy untuk siswa
    // Kita asumsikan 'foods' memiliki ID 1 s/d 5 dari FoodSeeder
    $data = [
      [
        'food_id'    => 1, // Nasi Ayam Bakar
        'nama'       => 'Budi Santoso',
        'kelas'      => 'Kelas 1', // Diubah dari Kelas 1A
        'created_at' => '2024-05-15 07:10:00',
        'updated_at' => '2024-05-15 07:10:00',
      ],
      [
        'food_id'    => 2, // Nasi Ikan Nila
        'nama'       => 'Citra Lestari',
        'kelas'      => 'Kelas 1', // Diubah dari Kelas 1B
        'created_at' => '2024-05-15 07:12:00',
        'updated_at' => '2024-05-15 07:12:00',
      ],
      [
        'food_id'    => 1, // Nasi Ayam Bakar (Sama dengan Budi)
        'nama'       => 'Doni Hidayat',
        'kelas'      => 'Kelas 2', // Diubah dari Kelas 2A
        'created_at' => '2024-05-16 08:00:00',
        'updated_at' => '2024-05-16 08:00:00',
      ],
      [
        'food_id'    => 3, // Nasi Telur Balado
        'nama'       => 'Eka Putri',
        'kelas'      => 'Kelas 2', // Diubah dari Kelas 2B
        'created_at' => '2024-05-16 08:05:00',
        'updated_at' => '2024-05-16 08:15:00', // Waktu update berbeda
      ],
      [
        'food_id'    => 4, // Nasi Semur Daging
        'nama'       => 'Fajar Nugroho',
        'kelas'      => 'Kelas 3', // Diubah dari Kelas 3A
        'created_at' => '2024-05-17 09:00:00',
        'updated_at' => '2024-05-17 09:00:00',
      ],
      [
        'food_id'    => 5, // Nasi Ayam Kecap
        'nama'       => 'Gita Permata',
        'kelas'      => 'Kelas 3', // Diubah dari Kelas 3B
        'created_at' => '2024-05-17 09:02:00',
        'updated_at' => '2024-05-17 09:02:00',
      ],
      [
        'food_id'    => null, // Siswa belum memilih menu
        'nama'       => 'Haris Maulana',
        'kelas'      => 'Kelas 1', // Diubah dari Kelas 1A
        'created_at' => '2024-05-18 07:30:00',
        'updated_at' => '2024-05-18 07:30:00',
      ],
    ];

    // Menggunakan Query Builder
    $this->db->table('students')->insertBatch($data);
  }
}
