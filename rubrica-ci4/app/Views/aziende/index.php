<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<nav>
    <a href="<?= site_url('contatti') ?>">← Rubrica</a>
    <a href="<?= site_url('aziende/create') ?>">+ Nuova azienda</a>
</nav>

<h2>Aziende</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (empty($aziende)): ?>
    <p>Nessuna azienda presente. <a href="<?= site_url('aziende/create') ?>">Aggiungi la prima azienda</a>.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Settore</th>
                <th>Telefono</th>
                <th>Email</th>
                <th>Sito web</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aziende as $az): ?>
            <tr>
                <td><?= esc($az['id']) ?></td>
                <td><?= esc($az['nome']) ?></td>
                <td><?= esc($az['settore'] ?? '') ?></td>
                <td><?= esc($az['telefono'] ?? '') ?></td>
                <td><?= esc($az['email'] ?? '') ?></td>
                <td>
                    <?php if (!empty($az['sito_web'])): ?>
                        <a href="<?= esc($az['sito_web']) ?>" target="_blank" rel="noopener noreferrer">
                            <?= esc($az['sito_web']) ?>
                        </a>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a href="<?= site_url('aziende/edit/' . $az['id']) ?>" class="btn-edit">Modifica</a>
                    <form method="post" action="<?= site_url('aziende/delete/' . $az['id']) ?>"
                          style="display:inline"
                          onsubmit="return confirm('Eliminare l\'azienda?')">
                        <?= csrf_field() ?>
                        <button type="submit" class="btn-delete">Elimina</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?= $this->endSection() ?>
