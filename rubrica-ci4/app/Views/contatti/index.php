<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<nav>
    <a href="<?= site_url('contatti/create') ?>">+ Nuovo contatto</a>
    <a href="<?= site_url('aziende') ?>">Gestisci aziende</a>
</nav>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (empty($contatti)): ?>
    <p>Nessun contatto presente. <a href="<?= site_url('contatti/create') ?>">Aggiungi il primo contatto</a>.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Cognome</th>
                <th>Nome</th>
                <th>Telefono</th>
                <th>Indirizzo</th>
                <th>Data di nascita</th>
                <th>Azienda</th>
                <th>Tag</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($contatti as $c): ?>
            <tr>
                <td><?= esc($c['id']) ?></td>
                <td><?= esc($c['cognome']) ?></td>
                <td><?= esc($c['nome']) ?></td>
                <td><?= esc($c['numero_telefono']) ?></td>
                <td><?= esc($c['indirizzo'] ?? '') ?></td>
                <td><?= esc($c['data_nascita'] ?? '') ?></td>
                <td><?= esc($c['nome_azienda'] ?? '') ?></td>
                <td>
                    <?php if (!empty($c['tags'])): ?>
                        <?php foreach (explode(', ', $c['tags']) as $tag_nome): ?>
                            <span class="tag-badge"><?= esc($tag_nome) ?></span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <a href="<?= site_url('contatti/edit/' . $c['id']) ?>" class="btn-edit">Modifica</a>
                    <form method="post" action="<?= site_url('contatti/delete/' . $c['id']) ?>"
                          style="display:inline"
                          onsubmit="return confirm('Eliminare il contatto?')">
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
