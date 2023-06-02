<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Contacts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'contact_id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'contact_title' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'contact_admin' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null' => true,
            ],
            'contact_mail' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_phone' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_mobile' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_map' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at datetime default CURRENT_TIMESTAMP',
            'updated_at datetime',
            'deleted_at datetime',

        ]);
        $this->forge->addKey('contact_id', true);
        $this->forge->createTable('contacts');
    }

    public function down()
    {
        $this->forge->dropTable('contacts');
    }
}
