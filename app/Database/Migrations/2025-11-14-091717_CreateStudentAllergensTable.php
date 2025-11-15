<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

// Tabel penghubung antara tabel students dan allergens dengan relasi Many to Many

class CreateStudentAllergensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'student_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'allergen_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);

        // Membuatnya unik
        $this->forge->addPrimaryKey(['student_id', 'allergen_id']);

        $this->forge->addForeignKey('student_id', 'students', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('allergen_id', 'allergens', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('student_allergens');
    }

    public function down()
    {
        $this->forge->dropTable('student_allergens');
    }
}
