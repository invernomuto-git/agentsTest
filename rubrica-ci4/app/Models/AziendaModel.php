<?php

namespace App\Models;

use CodeIgniter\Model;

class AziendaModel extends Model
{
    protected $table            = 'aziende';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nome',
        'settore',
        'indirizzo',
        'telefono',
        'email',
        'sito_web',
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
        'nome' => 'required|max_length[255]',
    ];
    protected $validationMessages = [
        'nome' => [
            'required'   => 'Il campo Nome è obbligatorio.',
            'max_length' => 'Il campo Nome non può superare 255 caratteri.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
