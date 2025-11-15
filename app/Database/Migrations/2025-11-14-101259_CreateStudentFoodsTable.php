<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

// Tabel penghubung antara tabel students dan foods dengan relasi Many to Many

class CreateStudentFoodsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'food_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);

        // Membuatnya unik
        $this->forge->addPrimaryKey(['student_id', 'food_id']);

        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('food_id', 'foods', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('student_foods');
    }

    public function down()
    {
        $this->forge->dropTable('student_foods');
    }
}
