<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<nav><a href="<?= site_url('contatti') ?>">← Lista contatti</a></nav>

<h2>Modifica contatto #<?= esc($contatto['id']) ?></h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= esc($err) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="post" action="<?= site_url('contatti/edit/' . $contatto['id']) ?>">
    <?= csrf_field() ?>
    <?php
    // When there are validation errors, use POST values; otherwise use DB values.
    $v = !empty($old) ? $old : $contatto;
    ?>
    <div class="form-group">
        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome"
               value="<?= esc($v['nome'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="cognome">Cognome *</label>
        <input type="text" id="cognome" name="cognome"
               value="<?= esc($v['cognome'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="numero_telefono">Numero di telefono *</label>
        <input type="text" id="numero_telefono" name="numero_telefono"
               value="<?= esc($v['numero_telefono'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="indirizzo">Indirizzo</label>
        <input type="text" id="indirizzo" name="indirizzo"
               value="<?= esc($v['indirizzo'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="data_nascita">Data di nascita</label>
        <input type="date" id="data_nascita" name="data_nascita"
               value="<?= esc($v['data_nascita'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="id_azienda">Azienda</label>
        <select id="id_azienda" name="id_azienda">
            <option value="">— Nessuna azienda —</option>
            <?php foreach ($aziende as $az): ?>
                <option value="<?= esc($az['id']) ?>"
                    <?= (!empty($v['id_azienda']) && (int) $v['id_azienda'] === (int) $az['id']) ? 'selected' : '' ?>>
                    <?= esc($az['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php if (!empty($tags_disponibili)): ?>
    <div class="form-group">
        <label>Tag</label>
        <div class="tag-checkboxes">
            <?php foreach ($tags_disponibili as $tag): ?>
                <label class="tag-checkbox-label">
                    <input type="checkbox" name="tags[]"
                           value="<?= esc($tag['id']) ?>"
                           <?= in_array((int) $tag['id'], $tag_ids_form, true) ? 'checked' : '' ?>>
                    <?= esc($tag['nome']) ?>
                </label>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    <button type="submit" class="btn-primary">Aggiorna contatto</button>
    <a href="<?= site_url('contatti') ?>" class="btn-cancel">Annulla</a>
</form>

<?= $this->endSection() ?>
