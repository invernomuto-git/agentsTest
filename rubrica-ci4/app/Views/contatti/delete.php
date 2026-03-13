<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<nav><a href="<?= site_url('contatti') ?>">← Lista contatti</a></nav>

<h2>Elimina contatto</h2>

<p>Sei sicuro di voler eliminare il contatto
    <strong><?= esc($contatto['cognome']) ?> <?= esc($contatto['nome']) ?></strong>
    (<?= esc($contatto['numero_telefono']) ?>)?
</p>

<form method="post" action="<?= site_url('contatti/delete/' . $contatto['id']) ?>">
    <?= csrf_field() ?>
    <button type="submit" class="btn-delete">Sì, elimina</button>
    <a href="<?= site_url('contatti') ?>" class="btn-cancel">Annulla</a>
</form>

<?= $this->endSection() ?>
