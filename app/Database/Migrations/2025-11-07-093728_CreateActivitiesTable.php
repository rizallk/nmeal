<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivitiesTable extends Migration
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
            'status' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'bahan_1_status' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'bahan_2_status' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'bahan_3_status' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'bahan_4_status' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'bahan_5_status' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'default' => false,
            ],
            'bahan_6_status' => [
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
        // Ini menghubungkan 'student_id' di tabel 'activity' ke 'id' di tabel 'students'
        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        // 'CASCADE' berarti jika student dihapus, semua activity terkait juga akan dihapus.

        $this->forge->createTable('activities');
    }

    public function down()
    {
        $this->forge->dropTable('activities');
    }
}
