<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        $this->call('TagsSeeder');
        $this->call('AziendeSeeder');
        $this->call('ContattiSeeder');
    }
}
