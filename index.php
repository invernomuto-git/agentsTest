<?php
require_once __DIR__ . '/db.php';

$message = '';
$messageType = '';

// Messaggio da redirect delete/error
if (isset($_GET['deleted'])) {
    $message = 'Contatto eliminato con successo.';
    $messageType = 'success';
} elseif (isset($_GET['error']) && $_GET['error'] === 'delete') {
    $message = 'Errore durante l\'eliminazione del contatto.';
    $messageType = 'error';
}

// Lettura contatti
try {
    $contatti = get_db()->query('SELECT * FROM contatti ORDER BY cognome, nome')->fetchAll();
} catch (PDOException $e) {
    $contatti = [];
    $message = 'Impossibile connettersi al database: ' . htmlspecialchars($e->getMessage());
    $messageType = 'error';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>

    <nav>
        <a href="create.php">+ Nuovo contatto</a>
    </nav>

    <?php if ($message): ?>
        <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if (empty($contatti)): ?>
        <p>Nessun contatto presente. <a href="create.php">Aggiungi il primo contatto</a>.</p>
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
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contatti as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['cognome']) ?></td>
                    <td><?= htmlspecialchars($c['nome']) ?></td>
                    <td><?= htmlspecialchars($c['numero_telefono']) ?></td>
                    <td><?= htmlspecialchars($c['indirizzo'] ?? '') ?></td>
                    <td><?= htmlspecialchars($c['data_nascita'] ?? '') ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= $c['id'] ?>" class="btn-edit">Modifica</a>
                        <a href="delete.php?id=<?= $c['id'] ?>" class="btn-delete"
                           onclick="return confirm('Eliminare il contatto?')">Elimina</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>
