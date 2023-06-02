<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sliders extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'slider_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'slider_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'slider_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'slider_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at datetime default CURRENT_TIMESTAMP',
            'updated_at datetime',
            'deleted_at datetime',

        ]);
        $this->forge->addKey('slider_id', true);
        $this->forge->createTable('sliders');
    }

    public function down()
    {
        $this->forge->dropTable('sliders');
    }
}
