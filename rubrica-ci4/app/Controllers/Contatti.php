<?php

namespace App\Controllers;

use App\Models\ContattoModel;
use App\Models\AziendaModel;
use App\Models\TagModel;

class Contatti extends BaseController
{
    protected ContattoModel $contattoModel;
    protected AziendaModel $aziendaModel;
    protected TagModel $tagModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->contattoModel = new ContattoModel();
        $this->aziendaModel  = new AziendaModel();
        $this->tagModel      = new TagModel();
    }

    /**
     * GET /contatti — List all contacts.
     */
    public function index(): string
    {
        $data = [
            'title'    => 'Rubrica Telefonica',
            'contatti' => $this->contattoModel->getContattiWithDetails(),
        ];

        return view('contatti/index', $data);
    }

    /**
     * GET  /contatti/create — Show create form.
     * POST /contatti/create — Process form submission.
     */
    public function create(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $aziende          = $this->aziendaModel->orderBy('nome')->findAll();
        $tags_disponibili = $this->tagModel->orderBy('id')->findAll();

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nome'            => 'required|max_length[100]',
                'cognome'         => 'required|max_length[100]',
                'numero_telefono' => 'required|max_length[30]',
            ];

            $messages = [
                'nome'            => ['required' => 'Il campo Nome è obbligatorio.'],
                'cognome'         => ['required' => 'Il campo Cognome è obbligatorio.'],
                'numero_telefono' => ['required' => 'Il campo Numero di telefono è obbligatorio.'],
            ];

            if (! $this->validate($rules, $messages)) {
                return view('contatti/create', [
                    'title'            => 'Nuovo contatto',
                    'aziende'          => $aziende,
                    'tags_disponibili' => $tags_disponibili,
                    'errors'           => $this->validator->getErrors(),
                    'old'              => $this->request->getPost(),
                ]);
            }

            $idAzienda = $this->request->getPost('id_azienda');
            $idAzienda = ($idAzienda !== '' && ctype_digit((string) $idAzienda)) ? (int) $idAzienda : null;

            $tagIds = $this->sanitizeTagIds($this->request->getPost('tags') ?? []);

            $db = \Config\Database::connect();
            $db->transStart();

            $this->contattoModel->skipValidation(true)->insert([
                'nome'            => $this->request->getPost('nome'),
                'cognome'         => $this->request->getPost('cognome'),
                'numero_telefono' => $this->request->getPost('numero_telefono'),
                'indirizzo'       => $this->request->getPost('indirizzo') ?: null,
                'data_nascita'    => $this->request->getPost('data_nascita') ?: null,
                'id_azienda'      => $idAzienda,
            ]);

            $newId = $this->contattoModel->getInsertID();
            $this->contattoModel->syncTags($newId, $tagIds);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return view('contatti/create', [
                    'title'            => 'Nuovo contatto',
                    'aziende'          => $aziende,
                    'tags_disponibili' => $tags_disponibili,
                    'errors'           => ['Errore durante il salvataggio del contatto.'],
                    'old'              => $this->request->getPost(),
                ]);
            }

            return redirect()->to('/contatti')->with('success', 'Contatto aggiunto con successo.');
        }

        return view('contatti/create', [
            'title'            => 'Nuovo contatto',
            'aziende'          => $aziende,
            'tags_disponibili' => $tags_disponibili,
            'errors'           => [],
            'old'              => [],
        ]);
    }

    /**
     * GET  /contatti/edit/:id — Show edit form.
     * POST /contatti/edit/:id — Process form submission.
     */
    public function edit(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $contatto = $this->contattoModel->find($id);

        if (! $contatto) {
            return redirect()->to('/contatti');
        }

        $aziende          = $this->aziendaModel->orderBy('nome')->findAll();
        $tags_disponibili = $this->tagModel->orderBy('id')->findAll();
        $tag_ids_correnti = $this->contattoModel->getTagIds($id);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'nome'            => 'required|max_length[100]',
                'cognome'         => 'required|max_length[100]',
                'numero_telefono' => 'required|max_length[30]',
            ];

            $messages = [
                'nome'            => ['required' => 'Il campo Nome è obbligatorio.'],
                'cognome'         => ['required' => 'Il campo Cognome è obbligatorio.'],
                'numero_telefono' => ['required' => 'Il campo Numero di telefono è obbligatorio.'],
            ];

            if (! $this->validate($rules, $messages)) {
                return view('contatti/edit', [
                    'title'            => 'Modifica contatto',
                    'contatto'         => $contatto,
                    'aziende'          => $aziende,
                    'tags_disponibili' => $tags_disponibili,
                    'tag_ids_form'     => $this->sanitizeTagIds($this->request->getPost('tags') ?? []),
                    'errors'           => $this->validator->getErrors(),
                    'old'              => $this->request->getPost(),
                ]);
            }

            $idAzienda = $this->request->getPost('id_azienda');
            $idAzienda = ($idAzienda !== '' && ctype_digit((string) $idAzienda)) ? (int) $idAzienda : null;

            $tagIds = $this->sanitizeTagIds($this->request->getPost('tags') ?? []);

            $db = \Config\Database::connect();
            $db->transStart();

            $this->contattoModel->skipValidation(true)->update($id, [
                'nome'            => $this->request->getPost('nome'),
                'cognome'         => $this->request->getPost('cognome'),
                'numero_telefono' => $this->request->getPost('numero_telefono'),
                'indirizzo'       => $this->request->getPost('indirizzo') ?: null,
                'data_nascita'    => $this->request->getPost('data_nascita') ?: null,
                'id_azienda'      => $idAzienda,
            ]);

            $this->contattoModel->syncTags($id, $tagIds);

            $db->transComplete();

            if ($db->transStatus() === false) {
                return view('contatti/edit', [
                    'title'            => 'Modifica contatto',
                    'contatto'         => $contatto,
                    'aziende'          => $aziende,
                    'tags_disponibili' => $tags_disponibili,
                    'tag_ids_form'     => $tagIds,
                    'errors'           => ['Errore durante l\'aggiornamento del contatto.'],
                    'old'              => $this->request->getPost(),
                ]);
            }

            return redirect()->to('/contatti')->with('success', 'Contatto aggiornato con successo.');
        }

        return view('contatti/edit', [
            'title'            => 'Modifica contatto',
            'contatto'         => $contatto,
            'aziende'          => $aziende,
            'tags_disponibili' => $tags_disponibili,
            'tag_ids_form'     => $tag_ids_correnti,
            'errors'           => [],
            'old'              => [],
        ]);
    }

    /**
     * GET  /contatti/delete/:id — Show delete confirmation.
     * POST /contatti/delete/:id — Process deletion.
     */
    public function delete(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $contatto = $this->contattoModel->find($id);

        if (! $contatto) {
            return redirect()->to('/contatti');
        }

        if ($this->request->getMethod() === 'POST') {
            $this->contattoModel->delete($id);
            return redirect()->to('/contatti')->with('success', 'Contatto eliminato con successo.');
        }

        return view('contatti/delete', [
            'title'    => 'Elimina contatto',
            'contatto' => $contatto,
        ]);
    }

    /**
     * Filters and returns only valid positive integer tag IDs.
     */
    private function sanitizeTagIds(array $raw): array
    {
        return array_values(array_filter(
            array_map('intval', $raw),
            fn ($v) => $v > 0
        ));
    }
}
