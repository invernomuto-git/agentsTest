<?php
require_once __DIR__ . '/db.php';

$message = '';
$messageType = '';

if (isset($_GET['deleted'])) {
    $message = 'Azienda eliminata con successo.';
    $messageType = 'success';
} elseif (isset($_GET['error']) && $_GET['error'] === 'delete') {
    $message = 'Errore durante l\'eliminazione dell\'azienda.';
    $messageType = 'error';
}

try {
    $aziende = get_db()->query('SELECT * FROM aziende ORDER BY nome')->fetchAll();
} catch (PDOException $e) {
    $aziende = [];
    $message = 'Impossibile connettersi al database: ' . htmlspecialchars($e->getMessage());
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aziende – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>

    <nav>
        <a href="index.php">← Lista contatti</a>
        <a href="create_azienda.php">+ Nuova azienda</a>
    </nav>

    <h2>Aziende</h2>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($aziende)): ?>
        <p>Nessuna azienda presente. <a href="create_azienda.php">Aggiungi la prima azienda</a>.</p>
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
                    <td><?= $az['id'] ?></td>
                    <td><?= htmlspecialchars($az['nome']) ?></td>
                    <td><?= htmlspecialchars($az['settore'] ?? '') ?></td>
                    <td><?= htmlspecialchars($az['telefono'] ?? '') ?></td>
                    <td><?= htmlspecialchars($az['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($az['sito_web'] ?? '') ?></td>
                    <td class="actions">
                        <a href="edit_azienda.php?id=<?= $az['id'] ?>" class="btn-edit">Modifica</a>
                        <a href="delete_azienda.php?id=<?= $az['id'] ?>" class="btn-delete"
                           onclick="return confirm('Eliminare l\'azienda?')">Elimina</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
