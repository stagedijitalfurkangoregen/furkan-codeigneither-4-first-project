<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Projects extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'project_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'project_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'project_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'project_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pg_group_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
            ],
            'created_at datetime default CURRENT_TIMESTAMP',
            'updated_at datetime',
            'deleted_at datetime',

        ]);
        $this->forge->addKey('project_id', true);
        $this->forge->createTable('projects');
    }

    public function down()
    {
        $this->forge->dropTable('projects');
    }
}
