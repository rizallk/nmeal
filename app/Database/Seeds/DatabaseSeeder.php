<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seeder utama untuk menjalankan semua seeder lain
   * dalam urutan yang benar.
   *
   * Perintah CLI untuk menjalankan:
   * php spark db:seed
   */
  public function run()
  {
    // Panggil seeder dengan urutan yang benar
    // untuk menghindari error foreign key
    $this->call('UserSeeder');
    $this->call('FoodSeeder');
    $this->call('StudentSeeder');
    $this->call('ActivitySeeder');
  }
}
