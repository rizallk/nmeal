<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

// Tabel penghubung antara tabel foods dan ingredients dengan relasi Many to Many

class CreateFoodIngredientsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'food_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'ingredient_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);

        // Membuatnya unik
        $this->forge->addPrimaryKey(['food_id', 'ingredient_id']);

        $this->forge->addForeignKey('food_id', 'foods', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('ingredient_id', 'ingredients', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('food_ingredients');
    }

    public function down()
    {
        $this->forge->dropTable('food_ingredients');
    }
}
