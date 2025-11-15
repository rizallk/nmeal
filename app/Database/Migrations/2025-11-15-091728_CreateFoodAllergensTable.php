<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

// Tabel penghubung antara tabel foods dan allergens dengan relasi Many to Many

class CreateFoodAllergensTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'food_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'allergen_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);

        // Membuatnya unik
        $this->forge->addPrimaryKey(['food_id', 'allergen_id']);

        $this->forge->addForeignKey('food_id', 'foods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('allergen_id', 'allergens', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('food_allergens');
    }

    public function down()
    {
        $this->forge->dropTable('student_allergens');
    }
}
