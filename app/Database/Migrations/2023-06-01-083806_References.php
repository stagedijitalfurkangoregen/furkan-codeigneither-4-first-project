<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class References extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'reference_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'reference_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'reference_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reference_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at datetime default CURRENT_TIMESTAMP',
            'updated_at datetime',
            'deleted_at datetime',

        ]);
        $this->forge->addKey('reference_id', true);
        $this->forge->createTable('references');
    }

    public function down()
    {
        $this->forge->dropTable('references');
    }
}
