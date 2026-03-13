<?php

namespace App\Models;

use CodeIgniter\Model;

class ContattoModel extends Model
{
    protected $table            = 'contatti';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome',
        'cognome',
        'numero_telefono',
        'indirizzo',
        'data_nascita',
        'id_azienda',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'nome'            => 'required|max_length[100]',
        'cognome'         => 'required|max_length[100]',
        'numero_telefono' => 'required|max_length[30]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required' => 'Il campo Nome è obbligatorio.',
        ],
        'cognome' => [
            'required' => 'Il campo Cognome è obbligatorio.',
        ],
        'numero_telefono' => [
            'required' => 'Il campo Numero di telefono è obbligatorio.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Returns all contacts with their company name and associated tags.
     */
    public function getContattiWithDetails(): array
    {
        return $this->db->query(
            'SELECT c.*, a.nome AS nome_azienda,
                    GROUP_CONCAT(t.nome ORDER BY t.id SEPARATOR \', \') AS tags
               FROM contatti c
               LEFT JOIN aziende a ON c.id_azienda = a.id
               LEFT JOIN contatti_tags ct ON c.id = ct.id_contatto
               LEFT JOIN tags t ON ct.id_tag = t.id
              GROUP BY c.id
              ORDER BY c.cognome, c.nome'
        )->getResultArray();
    }

    /**
     * Returns the tag IDs currently associated with a contact.
     */
    public function getTagIds(int $id): array
    {
        $rows = $this->db->table('contatti_tags')
            ->select('id_tag')
            ->where('id_contatto', $id)
            ->get()
            ->getResultArray();

        return array_map('intval', array_column($rows, 'id_tag'));
    }

    /**
     * Saves (replaces) tag associations for a contact inside an existing transaction.
     */
    public function syncTags(int $contattoId, array $tagIds): void
    {
        $this->db->table('contatti_tags')
            ->where('id_contatto', $contattoId)
            ->delete();

        if (!empty($tagIds)) {
            $rows = array_map(
                fn ($tagId) => ['id_contatto' => $contattoId, 'id_tag' => $tagId],
                $tagIds
            );
            $this->db->table('contatti_tags')->insertBatch($rows);
        }
    }
}
