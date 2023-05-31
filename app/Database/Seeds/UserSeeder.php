<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'user_name' => 'admin',
            'user_password'    => 'jlkfjsdl',
        ];

        // Simple Queries


        // Using Query Builder
        $this->db->table('users')->insert($data);
    }
}
