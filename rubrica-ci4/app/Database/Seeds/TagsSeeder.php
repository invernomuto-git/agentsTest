<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['nome' => 'lavoro'],
            ['nome' => 'scuola'],
            ['nome' => 'tempo libero'],
            ['nome' => 'bambini'],
        ];

        $this->db->table('tags')->insertBatch($data);
    }
}
