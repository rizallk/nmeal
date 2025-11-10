<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFoodsTable extends Migration
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
            'nama_menu' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'bahan_1' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
            ],
            'bahan_2' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
            ],
            'bahan_3' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
            ],
            'bahan_4' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
            ],
            'bahan_5' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
            ],
            'bahan_6' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
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
        $this->forge->createTable('foods');
    }

    public function down()
    {
        $this->forge->dropTable('foods');
    }
}
