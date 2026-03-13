<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<nav><a href="<?= site_url('aziende') ?>">← Lista aziende</a></nav>

<h2>Modifica azienda #<?= esc($azienda['id']) ?></h2>

<?php if (!empty($errors)): ?>
    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= esc($err) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<form method="post" action="<?= site_url('aziende/edit/' . $azienda['id']) ?>">
    <?= csrf_field() ?>
    <?php $v = !empty($old) ? $old : $azienda; ?>
    <div class="form-group">
        <label for="nome">Nome *</label>
        <input type="text" id="nome" name="nome"
               value="<?= esc($v['nome'] ?? '') ?>" required>
    </div>
    <div class="form-group">
        <label for="settore">Settore</label>
        <input type="text" id="settore" name="settore"
               value="<?= esc($v['settore'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="indirizzo">Indirizzo</label>
        <input type="text" id="indirizzo" name="indirizzo"
               value="<?= esc($v['indirizzo'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="telefono">Telefono</label>
        <input type="text" id="telefono" name="telefono"
               value="<?= esc($v['telefono'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"
               value="<?= esc($v['email'] ?? '') ?>">
    </div>
    <div class="form-group">
        <label for="sito_web">Sito web</label>
        <input type="url" id="sito_web" name="sito_web"
               value="<?= esc($v['sito_web'] ?? '') ?>">
    </div>
    <button type="submit" class="btn-primary">Aggiorna azienda</button>
    <a href="<?= site_url('aziende') ?>" class="btn-cancel">Annulla</a>
</form>

<?= $this->endSection() ?>
