<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFoodPickupsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'student_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'status' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'laporan' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        // Menambahkan Foreign Key
        // Ini menghubungkan 'student_id' di tabel 'food pickups' ke 'id' di tabel 'students'
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        // 'CASCADE' berarti jika student dihapus, semua food pickups terkait juga akan dihapus.

        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'SET NULL');
        // 'SET NULL' berarti jika user dihapus, semua food pickups terkait akan berubah menjadi null.

        $this->forge->createTable('food_pickups');
    }

    public function down()
    {
        $this->forge->dropTable('food_pickups');
    }
}
