<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FoodSeeder extends Seeder
{
  /**
   * Menjalankan proses seeding untuk tabel 'foods'.
   *
   * Perintah CLI untuk menjalankan seeder ini:
   * php spark db:seed FoodSeeder
   */
  public function run()
  {
    // Data dummy untuk menu makanan bergizi
    $data = [
      [
        'nama_menu'      => 'Nasi Ayam Bakar & Tumis Kangkung',
        'bahan_1'        => 'Nasi Putih',
        'bahan_2'        => 'Ayam Bakar (Paha)',
        'bahan_3'        => 'Tahu Goreng',
        'bahan_4'        => 'Tumis Kangkung',
        'bahan_5'        => 'Buah Pisang',
        'bahan_6'        => null,
        'created_at'     => '2024-05-10 08:15:00',
        'updated_at'     => '2024-05-10 08:15:00',
      ],
      [
        'nama_menu'      => 'Nasi Ikan Nila Asam Manis & Sayur Sop',
        'bahan_1'        => 'Nasi Putih',
        'bahan_2'        => 'Ikan Nila (Goreng)',
        'bahan_3'        => 'Saus Asam Manis',
        'bahan_4'        => 'Sayur Sop (Wortel, Buncis)',
        'bahan_5'        => 'Tempe Bacem',
        'bahan_6'        => 'Buah Jeruk',
        'created_at'     => '2024-05-11 09:00:00',
        'updated_at'     => '2024-05-11 09:20:00', // Waktu update sedikit berbeda
      ],
      [
        'nama_menu'      => 'Nasi Telur Balado Ringan & Perkedel',
        'bahan_1'        => 'Nasi Putih',
        'bahan_2'        => 'Telur Balado (Tidak Pedas)',
        'bahan_3'        => 'Perkedel Kentang',
        'bahan_4'        => 'Tumis Buncis Wortel',
        'bahan_5'        => 'Buah Semangka',
        'bahan_6'        => null,
        'created_at'     => '2024-05-12 08:30:00',
        'updated_at'     => '2024-05-12 08:30:00',
      ],
      [
        'nama_menu'      => 'Nasi Semur Daging Sapi & Tumis Bayam',
        'bahan_1'        => 'Nasi Putih',
        'bahan_2'        => 'Semur Daging Sapi',
        'bahan_3'        => 'Kentang (dalam semur)',
        'bahan_4'        => 'Tumis Bayam Jagung',
        'bahan_5'        => 'Tahu Kukus',
        'bahan_6'        => 'Buah Pepaya',
        'created_at'     => '2024-05-13 07:45:00',
        'updated_at'     => '2024-05-13 08:00:00', // Waktu update sedikit berbeda
      ],
      [
        'nama_menu'      => 'Nasi Ayam Kecap Manis & Capcay',
        'bahan_1'        => 'Nasi Putih',
        'bahan_2'        => 'Ayam Kecap (Dada)',
        'bahan_3'        => 'Capcay Kuah Ringan',
        'bahan_4'        => 'Bakwan Sayur',
        'bahan_5'        => 'Buah Apel',
        'bahan_6'        => null,
        'created_at'     => '2024-05-14 08:05:00',
        'updated_at'     => '2024-05-14 08:05:00',
      ],
    ];

    // Menggunakan Query Builder untuk memasukkan data secara batch
    // Ini lebih efisien daripada menggunakan insert() dalam loop
    $this->db->table('foods')->insertBatch($data);
  }
}
