<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AziendeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nome'    => 'Acme S.r.l.',
                'settore' => 'Tecnologia',
                'email'   => 'info@acme.it',
                'sito_web' => 'https://acme.it',
            ],
            [
                'nome'    => 'Beta Consulting',
                'settore' => 'Consulenza',
                'email'   => 'info@betaconsulting.it',
            ],
            [
                'nome'    => 'Gamma Foods',
                'settore' => 'Alimentare',
                'telefono' => '0612345678',
            ],
            [
                'nome'    => 'Delta Finance',
                'settore' => 'Finanza',
                'email'   => 'info@deltafinance.it',
            ],
            [
                'nome'    => 'Epsilon Media',
                'settore' => 'Media',
                'sito_web' => 'https://epsilonmedia.it',
            ],
        ];

        $this->db->table('aziende')->insertBatch($data);
    }
}
