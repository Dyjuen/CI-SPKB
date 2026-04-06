<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKriteriaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kriteria' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'bobot' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
            ],
            'tipe' => [
                'type'       => 'ENUM',
                'constraint' => ['B', 'C'],
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kriteria');
    }

    public function down()
    {
        $this->forge->dropTable('kriteria');
    }
}
