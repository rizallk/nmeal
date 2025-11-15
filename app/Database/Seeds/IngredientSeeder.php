<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class IngredientSeeder extends Seeder
{
  public function run()
  {
    $data = [
      // --- MINUMAN ---
      ['name' => 'Air Putih', 'created_at' => '2024-01-24 10:13:00', 'updated_at' => '2024-01-25 10:20:00'],

      // --- KARBOHIDRAT ---
      ['name' => 'Nasi Putih', 'created_at' => '2024-01-20 10:00:00', 'updated_at' => '2024-01-20 10:00:00'],
      ['name' => 'Kentang', 'created_at' => '2024-01-21 11:05:00', 'updated_at' => '2024-01-21 11:05:00'],
      ['name' => 'Mie Telur', 'created_at' => '2024-01-20 10:01:00', 'updated_at' => '2024-01-20 10:01:00'],
      ['name' => 'Singkong', 'created_at' => '2024-01-22 09:30:00', 'updated_at' => '2024-01-22 09:30:00'],
      ['name' => 'Ubi Jalar', 'created_at' => '2024-01-22 09:31:00', 'updated_at' => '2024-01-22 09:31:00'],
      ['name' => 'Tepung Terigu', 'created_at' => '2024-01-21 11:00:00', 'updated_at' => '2024-01-21 11:00:00'],

      // --- PROTEIN HEWANI ---
      ['name' => 'Daging Ayam', 'created_at' => '2024-02-05 14:00:00', 'updated_at' => '2024-02-05 14:00:00'],
      ['name' => 'Daging Sapi', 'created_at' => '2024-02-05 14:01:00', 'updated_at' => '2024-02-05 14:01:00'],
      ['name' => 'Telur Ayam', 'created_at' => '2024-02-07 10:01:00', 'updated_at' => '2024-02-07 10:01:00'],
      ['name' => 'Ikan Lele', 'created_at' => '2024-02-06 15:00:00', 'updated_at' => '2024-02-06 15:00:00'],
      ['name' => 'Ikan Kembung', 'created_at' => '2024-02-06 15:01:00', 'updated_at' => '2024-02-06 15:01:00'],
      ['name' => 'Ikan Tongkol', 'created_at' => '2024-02-07 10:00:00', 'updated_at' => '2024-02-07 10:00:00'],
      ['name' => 'Udang', 'created_at' => '2024-02-08 11:30:00', 'updated_at' => '2024-02-08 11:30:00'], // Tetap ada untuk variasi

      // --- PROTEIN NABATI ---
      ['name' => 'Tahu', 'created_at' => '2024-03-10 08:00:00', 'updated_at' => '2024-03-10 08:00:00'],
      ['name' => 'Tempe', 'created_at' => '2024-03-10 08:01:00', 'updated_at' => '2024-03-10 08:01:00'],
      ['name' => 'Kacang Tanah', 'created_at' => '2024-03-11 09:00:00', 'updated_at' => '2024-03-11 09:00:00'],
      ['name' => 'Kacang Merah', 'created_at' => '2024-03-11 09:01:00', 'updated_at' => '2024-03-11 09:01:00'],
      ['name' => 'Kacang Hijau', 'created_at' => '2024-03-12 10:30:00', 'updated_at' => '2024-03-12 10:30:00'],
      ['name' => 'Kacang Kedelai', 'created_at' => '2024-03-13 11:00:00', 'updated_at' => '2024-03-13 11:00:00'],

      // --- SAYURAN ---
      ['name' => 'Bayam', 'created_at' => '2024-05-02 10:01:00', 'updated_at' => '2024-05-02 10:01:00'],
      ['name' => 'Kangkung', 'created_at' => '2024-05-02 10:00:00', 'updated_at' => '2024-05-02 10:00:00'],
      ['name' => 'Wortel', 'created_at' => '2024-05-01 09:00:00', 'updated_at' => '2024-05-01 09:00:00'],
      ['name' => 'Buncis', 'created_at' => '2024-05-01 09:01:00', 'updated_at' => '2024-05-01 09:01:00'],
      ['name' => 'Labu Siam', 'created_at' => '2024-05-03 11:00:00', 'updated_at' => '2024-05-03 11:00:00'],
      ['name' => 'Kol (Kubis)', 'created_at' => '2024-05-03 11:01:00', 'updated_at' => '2024-05-03 11:01:00'],
      ['name' => 'Tauge (Kecambah)', 'created_at' => '2024-05-04 12:00:00', 'updated_at' => '2024-05-04 12:00:00'],
      ['name' => 'Timun', 'created_at' => '2024-05-04 12:01:00', 'updated_at' => '2024-05-04 12:01:00'],
      ['name' => 'Tomat', 'created_at' => '2024-04-22 15:00:00', 'updated_at' => '2024-04-22 15:00:00'],
      ['name' => 'Daun Bawang', 'created_at' => '2024-04-23 11:00:00', 'updated_at' => '2024-04-23 11:00:00'],
      ['name' => 'Seledri', 'created_at' => '2024-04-23 11:01:00', 'updated_at' => '2024-04-23 11:01:00'],

      // --- BUMBU DAPUR ---
      ['name' => 'Bawang Merah', 'created_at' => '2024-04-20 13:00:00', 'updated_at' => '2024-04-20 13:00:00'],
      ['name' => 'Bawang Putih', 'created_at' => '2024-04-20 13:01:00', 'updated_at' => '2024-04-20 13:01:00'],
      ['name' => 'Bawang Bombay', 'created_at' => '2024-04-20 13:02:00', 'updated_at' => '2024-04-20 13:02:00'],
      ['name' => 'Cabai Rawit', 'created_at' => '2024-04-21 14:00:00', 'updated_at' => '2024-04-21 14:00:00'],
      ['name' => 'Cabai Merah Besar', 'created_at' => '2024-04-21 14:01:00', 'updated_at' => '2024-04-21 14:01:00'],
      ['name' => 'Kunyit', 'created_at' => '2024-04-25 12:01:00', 'updated_at' => '2024-04-25 12:01:00'],
      ['name' => 'Jahe', 'created_at' => '2024-04-25 12:00:00', 'updated_at' => '2024-04-25 12:00:00'],
      ['name' => 'Lengkuas (Laos)', 'created_at' => '2024-04-26 09:00:00', 'updated_at' => '2024-04-26 09:00:00'],
      ['name' => 'Serai (Sereh)', 'created_at' => '2024-04-26 09:01:00', 'updated_at' => '2024-04-26 09:01:00'],
      ['name' => 'Daun Salam', 'created_at' => '2024-04-26 09:02:00', 'updated_at' => '2024-04-26 09:02:00'],
      ['name' => 'Kemiri', 'created_at' => '2024-04-27 10:00:00', 'updated_at' => '2024-04-27 10:00:00'],
      ['name' => 'Ketumbar', 'created_at' => '2024-04-27 10:01:00', 'updated_at' => '2024-04-27 10:01:00'],

      // --- PELENGKAP & LAIN-LAIN ---
      ['name' => 'Minyak Goreng', 'created_at' => '2024-06-18 17:00:00', 'updated_at' => '2024-06-18 17:00:00'],
      ['name' => 'Santan', 'created_at' => '2024-06-19 18:00:00', 'updated_at' => '2024-06-19 18:00:00'],
      ['name' => 'Margarin', 'created_at' => '2024-06-17 16:01:00', 'updated_at' => '2024-06-17 16:01:00'],
      ['name' => 'Garam', 'created_at' => '2024-07-01 09:00:00', 'updated_at' => '2024-07-01 09:00:00'],
      ['name' => 'Gula Pasir', 'created_at' => '2024-07-01 09:01:00', 'updated_at' => '2024-07-01 09:01:00'],
      ['name' => 'Lada Putih Bubuk', 'created_at' => '2024-07-02 10:00:00', 'updated_at' => '2024-07-02 10:00:00'],
      ['name' => 'Kecap Manis', 'created_at' => '2024-07-03 11:00:00', 'updated_at' => '2024-07-03 11:00:00'],
      ['name' => 'Kecap Asin', 'created_at' => '2024-07-03 11:01:00', 'updated_at' => '2024-07-03 11:01:00'],
      ['name' => 'Saus Tomat', 'created_at' => '2024-07-04 12:01:00', 'updated_at' => '2024-07-04 12:01:00'],
      ['name' => 'Cuka', 'created_at' => '2024-07-05 13:00:00', 'updated_at' => '2024-07-05 13:00:00'],
    ];

    // Masukkan data secara batch
    $this->db->table('ingredients')->insertBatch($data);
  }
}
