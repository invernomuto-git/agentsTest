<?php

namespace App\Controllers;

use App\Models\AziendaModel;

class Aziende extends BaseController
{
    protected AziendaModel $aziendaModel;

    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ): void {
        parent::initController($request, $response, $logger);
        $this->aziendaModel = new AziendaModel();
    }

    /**
     * GET /aziende — List all companies.
     */
    public function index(): string
    {
        return view('aziende/index', [
            'title'   => 'Gestione Aziende',
            'aziende' => $this->aziendaModel->orderBy('nome')->findAll(),
        ]);
    }

    /**
     * GET  /aziende/create — Show create form.
     * POST /aziende/create — Process form submission.
     */
    public function create(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if ($this->request->getMethod() === 'POST') {
            $rules    = ['nome' => 'required|max_length[255]'];
            $messages = ['nome' => ['required' => 'Il campo Nome è obbligatorio.']];

            if (! $this->validate($rules, $messages)) {
                return view('aziende/create', [
                    'title'  => 'Nuova azienda',
                    'errors' => $this->validator->getErrors(),
                    'old'    => $this->request->getPost(),
                ]);
            }

            $this->aziendaModel->insert([
                'nome'      => $this->request->getPost('nome'),
                'settore'   => $this->request->getPost('settore') ?: null,
                'indirizzo' => $this->request->getPost('indirizzo') ?: null,
                'telefono'  => $this->request->getPost('telefono') ?: null,
                'email'     => $this->request->getPost('email') ?: null,
                'sito_web'  => $this->request->getPost('sito_web') ?: null,
            ]);

            return redirect()->to('/aziende')->with('success', 'Azienda aggiunta con successo.');
        }

        return view('aziende/create', [
            'title'  => 'Nuova azienda',
            'errors' => [],
            'old'    => [],
        ]);
    }

    /**
     * GET  /aziende/edit/:id — Show edit form.
     * POST /aziende/edit/:id — Process form submission.
     */
    public function edit(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $azienda = $this->aziendaModel->find($id);

        if (! $azienda) {
            return redirect()->to('/aziende');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules    = ['nome' => 'required|max_length[255]'];
            $messages = ['nome' => ['required' => 'Il campo Nome è obbligatorio.']];

            if (! $this->validate($rules, $messages)) {
                return view('aziende/edit', [
                    'title'   => 'Modifica azienda',
                    'azienda' => $azienda,
                    'errors'  => $this->validator->getErrors(),
                    'old'     => $this->request->getPost(),
                ]);
            }

            $this->aziendaModel->update($id, [
                'nome'      => $this->request->getPost('nome'),
                'settore'   => $this->request->getPost('settore') ?: null,
                'indirizzo' => $this->request->getPost('indirizzo') ?: null,
                'telefono'  => $this->request->getPost('telefono') ?: null,
                'email'     => $this->request->getPost('email') ?: null,
                'sito_web'  => $this->request->getPost('sito_web') ?: null,
            ]);

            return redirect()->to('/aziende')->with('success', 'Azienda aggiornata con successo.');
        }

        return view('aziende/edit', [
            'title'   => 'Modifica azienda',
            'azienda' => $azienda,
            'errors'  => [],
            'old'     => [],
        ]);
    }

    /**
     * GET  /aziende/delete/:id — Show delete confirmation.
     * POST /aziende/delete/:id — Process deletion.
     */
    public function delete(int $id): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $azienda = $this->aziendaModel->find($id);

        if (! $azienda) {
            return redirect()->to('/aziende');
        }

        if ($this->request->getMethod() === 'POST') {
            $this->aziendaModel->delete($id);
            return redirect()->to('/aziende')->with('success', 'Azienda eliminata con successo.');
        }

        return view('aziende/delete', [
            'title'   => 'Elimina azienda',
            'azienda' => $azienda,
        ]);
    }
}
