<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivitySeeder extends Seeder
{
  /**
   * Menjalankan proses seeding untuk tabel 'activities'.
   *
   * PERHATIAN: Seeder ini bergantung pada StudentSeeder.
   * Pastikan StudentSeeder sudah dijalankan terlebih dahulu.
   *
   * Perintah CLI untuk menjalankan seeder ini:
   * php spark db:seed ActivitySeeder
   */
  public function run()
  {
    // Data dummy untuk aktivitas siswa
    // Kita asumsikan 'students' memiliki ID 1 s/d 7 dari StudentSeeder
    $data = [
      [
        'student_id'     => 1, // Budi Santoso
        'status'         => true, // Selesai
        'bahan_1_status' => true,
        'bahan_2_status' => true,
        'bahan_3_status' => true,
        'bahan_4_status' => true,
        'bahan_5_status' => true,
        'bahan_6_status' => false, // Menu ini hanya 5 bahan
        'laporan'        => 'Kegiatan berjalan lancar, Budi suka ayam bakar.',
        'created_at'     => '2024-05-15 10:00:00',
        'updated_at'     => '2024-05-15 10:00:00',
      ],
      [
        'student_id'     => 2, // Citra Lestari
        'status'         => true, // Selesai
        'bahan_1_status' => true,
        'bahan_2_status' => true,
        'bahan_3_status' => true,
        'bahan_4_status' => true,
        'bahan_5_status' => true,
        'bahan_6_status' => true,
        'laporan'        => 'Semua bahan lengkap dan disukai.',
        'created_at'     => '2024-05-15 10:05:00',
        'updated_at'     => '2024-05-15 10:05:00',
      ],
      [
        'student_id'     => 3, // Doni Hidayat
        'status'         => false, // Belum Selesai / Sedang Berlangsung
        'bahan_1_status' => true,  // Nasi
        'bahan_2_status' => true,  // Ayam
        'bahan_3_status' => false, // Tahu (belum)
        'bahan_4_status' => false, // Kangkung (belum)
        'bahan_5_status' => true,  // Pisang
        'bahan_6_status' => false,
        'laporan'        => 'Masih menunggu tahu goreng matang.',
        'created_at'     => '2024-05-16 10:15:00',
        'updated_at'     => '2024-05-16 10:30:00',
      ],
      [
        'student_id'     => 4, // Eka Putri
        'status'         => true, // Selesai
        'bahan_1_status' => true,
        'bahan_2_status' => true,
        'bahan_3_status' => true,
        'bahan_4_status' => true,
        'bahan_5_status' => true,
        'bahan_6_status' => false,
        'laporan'        => 'Selesai. Telur balado tidak pedas, aman.',
        'created_at'     => '2024-05-16 10:20:00',
        'updated_at'     => '2024-05-16 10:20:00',
      ],
      [
        'student_id'     => 5, // Fajar Nugroho
        'status'         => false, // Belum Selesai
        'bahan_1_status' => true,
        'bahan_2_status' => false, // Daging masih dimasak
        'bahan_3_status' => false,
        'bahan_4_status' => false,
        'bahan_5_status' => false,
        'bahan_6_status' => false,
        'laporan'        => null, // Belum ada laporan
        'created_at'     => '2024-05-17 10:00:00',
        'updated_at'     => '2024-05-17 10:00:00',
      ],
      [
        'student_id'     => 6, // Gita Permata
        'status'         => true, // Selesai
        'bahan_1_status' => true,
        'bahan_2_status' => true,
        'bahan_3_status' => true,
        'bahan_4_status' => true,
        'bahan_5_status' => true,
        'bahan_6_status' => false,
        'laporan'        => 'Capcay sayuran lengkap dan segar.',
        'created_at'     => '2024-05-17 10:05:00',
        'updated_at'     => '2024-05-17 10:05:00',
      ],
      [
        'student_id'     => 7, // Haris Maulana
        'status'         => false, // Belum mulai (karena menu food_id=null)
        'bahan_1_status' => false,
        'bahan_2_status' => false,
        'bahan_3_status' => false,
        'bahan_4_status' => false,
        'bahan_5_status' => false,
        'bahan_6_status' => false,
        'laporan'        => 'Siswa belum menentukan pilihan menu.',
        'created_at'     => '2024-05-18 08:00:00',
        'updated_at'     => '2024-05-18 08:00:00',
      ],
    ];

    // Menggunakan Query Builder
    $this->db->table('activities')->insertBatch($data);
  }
}
