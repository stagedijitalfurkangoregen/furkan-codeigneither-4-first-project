<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProjectGroups extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'pg_group_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'pg_group_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'pg_group_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pg_group_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pg_top_group' => [
                'type'           => 'INT',
                'constraint'     => 5,
            ],
            'created_at datetime default CURRENT_TIMESTAMP',
            'updated_at datetime',
            'deleted_at datetime',

        ]);
        $this->forge->addKey('pg_group_id', true);
        $this->forge->createTable('pg_groups');
    }

    public function down()
    {
        $this->forge->dropTable('pg_groups');
    }
}
