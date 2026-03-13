<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Rubrica Telefonica') ?> – Rubrica Telefonica</title>
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
</head>
<body>
    <h1>Rubrica Telefonica</h1>
    <?= $this->renderSection('content') ?>
</body>
</html>
