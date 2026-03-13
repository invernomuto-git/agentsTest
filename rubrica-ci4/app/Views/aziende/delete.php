<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<nav><a href="<?= site_url('aziende') ?>">← Lista aziende</a></nav>

<h2>Elimina azienda</h2>

<p>Sei sicuro di voler eliminare l'azienda
    <strong><?= esc($azienda['nome']) ?></strong>?
</p>
<p><small>I contatti associati a questa azienda perderanno il collegamento, ma non saranno eliminati.</small></p>

<form method="post" action="<?= site_url('aziende/delete/' . $azienda['id']) ?>">
    <?= csrf_field() ?>
    <button type="submit" class="btn-delete">Sì, elimina</button>
    <a href="<?= site_url('aziende') ?>" class="btn-cancel">Annulla</a>
</form>

<?= $this->endSection() ?>
