<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateContattiTagsTable extends Migration
{
    public function up(): void
    {
        $this->forge->addField([
            'id_contatto' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'id_tag' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey(['id_contatto', 'id_tag']);
        $this->forge->addForeignKey('id_contatto', 'contatti', 'id', '', 'CASCADE');
        $this->forge->addForeignKey('id_tag', 'tags', 'id', '', 'CASCADE');
        $this->forge->createTable('contatti_tags');
    }

    public function down(): void
    {
        $this->forge->dropTable('contatti_tags');
    }
}
