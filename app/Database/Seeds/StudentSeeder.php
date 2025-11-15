<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StudentSeeder extends Seeder
{
  public function run()
  {
    $data = [
      // --- Kelas 1 (15 Siswa) ---
      ['nis' => '0051234567', 'nama_lengkap' => 'Budi Santoso', 'kelas' => '1', 'created_at' => '2024-07-15 07:10:00', 'updated_at' => '2024-07-15 07:10:00'],
      ['nis' => '0051234568', 'nama_lengkap' => 'Citra Lestari', 'kelas' => '1', 'created_at' => '2024-07-15 07:12:00', 'updated_at' => '2024-07-15 07:12:00'],
      ['nis' => '0051234573', 'nama_lengkap' => 'Haris Maulana', 'kelas' => '1', 'created_at' => '2024-07-15 07:14:00', 'updated_at' => '2024-07-15 07:14:00'],
      ['nis' => '0051234580', 'nama_lengkap' => 'Ahmad Jalaludin', 'kelas' => '1', 'created_at' => '2024-07-15 07:15:00', 'updated_at' => '2024-07-15 07:15:00'],
      ['nis' => '0051234581', 'nama_lengkap' => 'Bella Ananda', 'kelas' => '1', 'created_at' => '2024-07-15 07:16:00', 'updated_at' => '2024-07-15 07:16:00'],
      ['nis' => '0051234582', 'nama_lengkap' => 'Candra Wijaya', 'kelas' => '1', 'created_at' => '2024-07-15 07:17:00', 'updated_at' => '2024-07-15 07:17:00'],
      ['nis' => '0051234583', 'nama_lengkap' => 'Dian Puspita', 'kelas' => '1', 'created_at' => '2024-07-15 07:18:00', 'updated_at' => '2024-07-15 07:18:00'],
      ['nis' => '0051234584', 'nama_lengkap' => 'Elang Perkasa', 'kelas' => '1', 'created_at' => '2024-07-15 07:19:00', 'updated_at' => '2024-07-15 07:19:00'],
      ['nis' => '0051234585', 'nama_lengkap' => 'Fira Assegaf', 'kelas' => '1', 'created_at' => '2024-07-15 07:20:00', 'updated_at' => '2024-07-15 07:20:00'],
      ['nis' => '0051234586', 'nama_lengkap' => 'Gilang Ramadhan', 'kelas' => '1', 'created_at' => '2024-07-15 07:21:00', 'updated_at' => '2024-07-15 07:21:00'],
      ['nis' => '0051234587', 'nama_lengkap' => 'Hana Malika', 'kelas' => '1', 'created_at' => '2024-07-15 07:22:00', 'updated_at' => '2024-07-15 07:22:00'],
      ['nis' => '0051234588', 'nama_lengkap' => 'Indra Gunawan', 'kelas' => '1', 'created_at' => '2024-07-15 07:23:00', 'updated_at' => '2024-07-15 07:23:00'],
      ['nis' => '0051234589', 'nama_lengkap' => 'Joko Susilo', 'kelas' => '1', 'created_at' => '2024-07-15 07:24:00', 'updated_at' => '2024-07-15 07:24:00'],
      ['nis' => '0051234590', 'nama_lengkap' => 'Kania Dewi', 'kelas' => '1', 'created_at' => '2024-07-15 07:25:00', 'updated_at' => '2024-07-15 07:25:00'],
      ['nis' => '0051234591', 'nama_lengkap' => 'Lutfi Hakim', 'kelas' => '1', 'created_at' => '2024-07-15 07:26:00', 'updated_at' => '2024-07-15 07:26:00'],

      // --- Kelas 2 (15 Siswa) ---
      ['nis' => '0041234569', 'nama_lengkap' => 'Doni Hidayat', 'kelas' => '2', 'created_at' => '2024-07-16 08:00:00', 'updated_at' => '2024-07-16 08:00:00'],
      ['nis' => '0041234570', 'nama_lengkap' => 'Eka Putri', 'kelas' => '2', 'created_at' => '2024-07-16 08:05:00', 'updated_at' => '2024-07-16 08:15:00'],
      ['nis' => '0041234580', 'nama_lengkap' => 'Mega Anggraini', 'kelas' => '2', 'created_at' => '2024-07-16 08:10:00', 'updated_at' => '2024-07-16 08:10:00'],
      ['nis' => '0041234581', 'nama_lengkap' => 'Nanda Pratama', 'kelas' => '2', 'created_at' => '2024-07-16 08:11:00', 'updated_at' => '2024-07-16 08:11:00'],
      ['nis' => '0041234582', 'nama_lengkap' => 'Olivia Rahman', 'kelas' => '2', 'created_at' => '2024-07-16 08:12:00', 'updated_at' => '2024-07-16 08:12:00'],
      ['nis' => '0041234583', 'nama_lengkap' => 'Pandu Winata', 'kelas' => '2', 'created_at' => '2024-07-16 08:13:00', 'updated_at' => '2024-07-16 08:13:00'],
      ['nis' => '0041234584', 'nama_lengkap' => 'Qory Sandioriva', 'kelas' => '2', 'created_at' => '2024-07-16 08:14:00', 'updated_at' => '2024-07-16 08:14:00'],
      ['nis' => '0041234585', 'nama_lengkap' => 'Rangga Saputra', 'kelas' => '2', 'created_at' => '2024-07-16 08:15:00', 'updated_at' => '2024-07-16 08:15:00'],
      ['nis' => '0041234586', 'nama_lengkap' => 'Sinta Dewi', 'kelas' => '2', 'created_at' => '2024-07-16 08:16:00', 'updated_at' => '2024-07-16 08:16:00'],
      ['nis' => '0041234587', 'nama_lengkap' => 'Tora Sudiro', 'kelas' => '2', 'created_at' => '2024-07-16 08:17:00', 'updated_at' => '2024-07-16 08:17:00'],
      ['nis' => '0041234588', 'nama_lengkap' => 'Vino Bastian', 'kelas' => '2', 'created_at' => '2024-07-16 08:18:00', 'updated_at' => '2024-07-16 08:18:00'],
      ['nis' => '0041234589', 'nama_lengkap' => 'Wulan Guritno', 'kelas' => '2', 'created_at' => '2024-07-16 08:19:00', 'updated_at' => '2024-07-16 08:19:00'],
      ['nis' => '0041234590', 'nama_lengkap' => 'Yoga Pratama', 'kelas' => '2', 'created_at' => '2024-07-16 08:20:00', 'updated_at' => '2024-07-16 08:20:00'],
      ['nis' => '0041234591', 'nama_lengkap' => 'Zaskia Mecca', 'kelas' => '2', 'created_at' => '2024-07-16 08:21:00', 'updated_at' => '2024-07-16 08:21:00'],
      ['nis' => '0041234592', 'nama_lengkap' => 'Reza Rahadian', 'kelas' => '2', 'created_at' => '2024-07-16 08:22:00', 'updated_at' => '2024-07-16 08:22:00'],

      // --- Kelas 3 (15 Siswa) ---
      ['nis' => '0031234571', 'nama_lengkap' => 'Fajar Nugroho', 'kelas' => '3', 'created_at' => '2024-07-17 09:00:00', 'updated_at' => '2024-07-17 09:00:00'],
      ['nis' => '0031234572', 'nama_lengkap' => 'Gita Permata', 'kelas' => '3', 'created_at' => '2024-07-17 09:02:00', 'updated_at' => '2024-07-17 09:02:00'],
      ['nis' => '0031234580', 'nama_lengkap' => 'Agung Sedayu', 'kelas' => '3', 'created_at' => '2024-07-17 09:03:00', 'updated_at' => '2024-07-17 09:03:00'],
      ['nis' => '0031234581', 'nama_lengkap' => 'Bunga Citra', 'kelas' => '3', 'created_at' => '2024-07-17 09:04:00', 'updated_at' => '2024-07-17 09:04:00'],
      ['nis' => '0031234582', 'nama_lengkap' => 'Chandra Liow', 'kelas' => '3', 'created_at' => '2024-07-17 09:05:00', 'updated_at' => '2024-07-17 09:05:00'],
      ['nis' => '0031234583', 'nama_lengkap' => 'Dimas Anggara', 'kelas' => '3', 'created_at' => '2024-07-17 09:06:00', 'updated_at' => '2024-07-17 09:06:00'],
      ['nis' => '0031234584', 'nama_lengkap' => 'Eva Celia', 'kelas' => '3', 'created_at' => '2024-07-17 09:07:00', 'updated_at' => '2024-07-17 09:07:00'],
      ['nis' => '0031234585', 'nama_lengkap' => 'Febby Rastanty', 'kelas' => '3', 'created_at' => '2024-07-17 09:08:00', 'updated_at' => '2024-07-17 09:08:00'],
      ['nis' => '0031234586', 'nama_lengkap' => 'Gading Marten', 'kelas' => '3', 'created_at' => '2024-07-17 09:09:00', 'updated_at' => '2024-07-17 09:09:00'],
      ['nis' => '0031234587', 'nama_lengkap' => 'Hamish Daud', 'kelas' => '3', 'created_at' => '2024-07-17 09:10:00', 'updated_at' => '2024-07-17 09:10:00'],
      ['nis' => '0031234588', 'nama_lengkap' => 'Iqbaal Ramadhan', 'kelas' => '3', 'created_at' => '2024-07-17 09:11:00', 'updated_at' => '2024-07-17 09:11:00'],
      ['nis' => '0031234589', 'nama_lengkap' => 'Jessica Mila', 'kelas' => '3', 'created_at' => '2024-07-17 09:12:00', 'updated_at' => '2024-07-17 09:12:00'],
      ['nis' => '0031234590', 'nama_lengkap' => 'Kevin Julio', 'kelas' => '3', 'created_at' => '2024-07-17 09:13:00', 'updated_at' => '2024-07-17 09:13:00'],
      ['nis' => '0031234591', 'nama_lengkap' => 'Laudya Cynthia Bella', 'kelas' => '3', 'created_at' => '2024-07-17 09:14:00', 'updated_at' => '2024-07-17 09:14:00'],
      ['nis' => '0031234592', 'nama_lengkap' => 'Morgan Oey', 'kelas' => '3', 'created_at' => '2024-07-17 09:15:00', 'updated_at' => '2024-07-17 09:15:00'],

      // --- Kelas 4 (15 Siswa) ---
      ['nis' => '0021234567', 'nama_lengkap' => 'Nino Fernandez', 'kelas' => '4', 'created_at' => '2024-07-18 10:00:00', 'updated_at' => '2024-07-18 10:00:00'],
      ['nis' => '0021234568', 'nama_lengkap' => 'Omar Daniel', 'kelas' => '4', 'created_at' => '2024-07-18 10:01:00', 'updated_at' => '2024-07-18 10:01:00'],
      ['nis' => '0021234569', 'nama_lengkap' => 'Pevita Pearce', 'kelas' => '4', 'created_at' => '2024-07-18 10:02:00', 'updated_at' => '2024-07-18 10:02:00'],
      ['nis' => '0021234570', 'nama_lengkap' => 'Raisa Andriana', 'kelas' => '4', 'created_at' => '2024-07-18 10:03:00', 'updated_at' => '2024-07-18 10:03:00'],
      ['nis' => '0021234571', 'nama_lengkap' => 'Samuel Zylgwyn', 'kelas' => '4', 'created_at' => '2024-07-18 10:04:00', 'updated_at' => '2024-07-18 10:04:00'],
      ['nis' => '0021234572', 'nama_lengkap' => 'Tatjana Saphira', 'kelas' => '4', 'created_at' => '2024-07-18 10:05:00', 'updated_at' => '2024-07-18 10:05:00'],
      ['nis' => '0021234573', 'nama_lengkap' => 'Vidi Aldiano', 'kelas' => '4', 'created_at' => '2024-07-18 10:06:00', 'updated_at' => '2024-07-18 10:06:00'],
      ['nis' => '0021234574', 'nama_lengkap' => 'Wafda Saifan', 'kelas' => '4', 'created_at' => '2024-07-18 10:07:00', 'updated_at' => '2024-07-18 10:07:00'],
      ['nis' => '0021234575', 'nama_lengkap' => 'Yuki Kato', 'kelas' => '4', 'created_at' => '2024-07-18 10:08:00', 'updated_at' => '2024-07-18 10:08:00'],
      ['nis' => '0021234576', 'nama_lengkap' => 'Zulfa Maharani', 'kelas' => '4', 'created_at' => '2024-07-18 10:09:00', 'updated_at' => '2024-07-18 10:09:00'],
      ['nis' => '0021234577', 'nama_lengkap' => 'Aliando Syarief', 'kelas' => '4', 'created_at' => '2024-07-18 10:10:00', 'updated_at' => '2024-07-18 10:10:00'],
      ['nis' => '0021234578', 'nama_lengkap' => 'Bryan Domani', 'kelas' => '4', 'created_at' => '2024-07-18 10:11:00', 'updated_at' => '2024-07-18 10:11:00'],
      ['nis' => '0021234579', 'nama_lengkap' => 'Chelsea Islan', 'kelas' => '4', 'created_at' => '2024-07-18 10:12:00', 'updated_at' => '2024-07-18 10:12:00'],
      ['nis' => '0021234580', 'nama_lengkap' => 'Deva Mahenra', 'kelas' => '4', 'created_at' => '2024-07-18 10:13:00', 'updated_at' => '2024-07-18 10:13:00'],
      ['nis' => '0021234581', 'nama_lengkap' => 'Ernest Prakasa', 'kelas' => '4', 'created_at' => '2024-07-18 10:14:00', 'updated_at' => '2024-07-18 10:14:00'],

      // --- Kelas 5 (15 Siswa) ---
      ['nis' => '0011234567', 'nama_lengkap' => 'Adipati Dolken', 'kelas' => '5', 'created_at' => '2024-07-19 11:00:00', 'updated_at' => '2024-07-19 11:00:00'],
      ['nis' => '0011234568', 'nama_lengkap' => 'Acha Septriasa', 'kelas' => '5', 'created_at' => '2024-07-19 11:01:00', 'updated_at' => '2024-07-19 11:01:00'],
      ['nis' => '0011234569', 'nama_lengkap' => 'Abimana Aryasatya', 'kelas' => '5', 'created_at' => '2024-07-19 11:02:00', 'updated_at' => '2024-07-19 11:02:00'],
      ['nis' => '0011234570', 'nama_lengkap' => 'Arifin Putra', 'kelas' => '5', 'created_at' => '2024-07-19 11:03:00', 'updated_at' => '2024-07-19 11:03:00'],
      ['nis' => '0011234571', 'nama_lengkap' => 'Atiqah Hasiholan', 'kelas' => '5', 'created_at' => '2024-07-19 11:04:00', 'updated_at' => '2024-07-19 11:04:00'],
      ['nis' => '0011234572', 'nama_lengkap' => 'Chicco Jerikho', 'kelas' => '5', 'created_at' => '2024-07-19 11:05:00', 'updated_at' => '2024-07-19 11:05:00'],
      ['nis' => '0011234573', 'nama_lengkap' => 'Dian Sastrowardoyo', 'kelas' => '5', 'created_at' => '2024-07-19 11:06:00', 'updated_at' => '2024-07-19 11:06:00'],
      ['nis' => '0011234574', 'nama_lengkap' => 'Fachri Albar', 'kelas' => '5', 'created_at' => '2024-07-19 11:07:00', 'updated_at' => '2024-07-19 11:07:00'],
      ['nis' => '0011234575', 'nama_lengkap' => 'Herjunot Ali', 'kelas' => '5', 'created_at' => '2024-07-19 11:08:00', 'updated_at' => '2024-07-19 11:08:00'],
      ['nis' => '0011234576', 'nama_lengkap' => 'Lukman Sardi', 'kelas' => '5', 'created_at' => '2024-07-19 11:09:00', 'updated_at' => '2024-07-19 11:09:00'],
      ['nis' => '0011234577', 'nama_lengkap' => 'Marsha Timothy', 'kelas' => '5', 'created_at' => '2024-07-19 11:10:00', 'updated_at' => '2024-07-19 11:10:00'],
      ['nis' => '0011234578', 'nama_lengkap' => 'Nicholas Saputra', 'kelas' => '5', 'created_at' => '2024-07-19 11:11:00', 'updated_at' => '2024-07-19 11:11:00'],
      ['nis' => '0011234579', 'nama_lengkap' => 'Prisia Nasution', 'kelas' => '5', 'created_at' => '2024-07-19 11:12:00', 'updated_at' => '2024-07-19 11:12:00'],
      ['nis' => '0011234580', 'nama_lengkap' => 'Rio Dewanto', 'kelas' => '5', 'created_at' => '2024-07-19 11:13:00', 'updated_at' => '2024-07-19 11:13:00'],
      ['nis' => '0011234581', 'nama_lengkap' => 'Titi Kamal', 'kelas' => '5', 'created_at' => '2024-07-19 11:14:00', 'updated_at' => '2024-07-19 11:14:00'],
    ];

    $this->db->table('students')->insertBatch($data);
  }
}
