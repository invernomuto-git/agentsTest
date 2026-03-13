<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ContattiSeeder extends Seeder
{
    public function run(): void
    {
        $contatti = [
            [
                'nome'            => 'Mario',
                'cognome'         => 'Rossi',
                'numero_telefono' => '3331234567',
                'indirizzo'       => 'Via Roma 1, Milano',
                'data_nascita'    => '1985-03-15',
                'id_azienda'      => 1,
            ],
            [
                'nome'            => 'Laura',
                'cognome'         => 'Bianchi',
                'numero_telefono' => '3469876543',
                'indirizzo'       => 'Corso Italia 22, Roma',
                'data_nascita'    => '1990-07-22',
                'id_azienda'      => 2,
            ],
            [
                'nome'            => 'Giuseppe',
                'cognome'         => 'Verdi',
                'numero_telefono' => '3280001122',
                'indirizzo'       => 'Viale Po 5, Torino',
                'id_azienda'      => 3,
            ],
            [
                'nome'            => 'Anna',
                'cognome'         => 'Neri',
                'numero_telefono' => '3475556677',
                'data_nascita'    => '1978-11-30',
            ],
            [
                'nome'            => 'Carlo',
                'cognome'         => 'Esposito',
                'numero_telefono' => '3908889900',
                'indirizzo'       => 'Piazza Garibaldi 3, Napoli',
            ],
        ];

        $this->db->table('contatti')->insertBatch($contatti);

        // Associa alcuni tag ai contatti
        $tagAssociations = [
            ['id_contatto' => 1, 'id_tag' => 1], // Mario → lavoro
            ['id_contatto' => 1, 'id_tag' => 3], // Mario → tempo libero
            ['id_contatto' => 2, 'id_tag' => 1], // Laura → lavoro
            ['id_contatto' => 3, 'id_tag' => 1], // Giuseppe → lavoro
            ['id_contatto' => 4, 'id_tag' => 2], // Anna → scuola
            ['id_contatto' => 4, 'id_tag' => 4], // Anna → bambini
            ['id_contatto' => 5, 'id_tag' => 3], // Carlo → tempo libero
        ];

        $this->db->table('contatti_tags')->insertBatch($tagAssociations);
    }
}
