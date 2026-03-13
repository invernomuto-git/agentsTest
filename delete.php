<?php
require_once __DIR__ . '/db.php';

// Verifica che l'ID sia valido
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = (int) $_GET['id'];

// Recupera il contatto per mostrarne i dati nel riepilogo
try {
    $stmt = get_db()->prepare('SELECT * FROM contatti WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $contatto = $stmt->fetch();
} catch (PDOException $e) {
    $contatto = null;
}

if (!$contatto) {
    header('Location: index.php');
    exit;
}

// Conferma ed esegui la cancellazione
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm'])) {
    try {
        $stmt = get_db()->prepare('DELETE FROM contatti WHERE id = :id');
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        // In caso di errore, torna alla lista con messaggio
        header('Location: index.php?error=delete');
        exit;
    }
    header('Location: index.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elimina contatto – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>
    <nav><a href="index.php">← Lista contatti</a></nav>

    <h2>Elimina contatto</h2>

    <p>Sei sicuro di voler eliminare il seguente contatto?</p>
    <table style="max-width:480px; margin: 16px 0;">
        <tr><th>Nome</th><td><?= htmlspecialchars($contatto['nome']) ?></td></tr>
        <tr><th>Cognome</th><td><?= htmlspecialchars($contatto['cognome']) ?></td></tr>
        <tr><th>Telefono</th><td><?= htmlspecialchars($contatto['numero_telefono']) ?></td></tr>
        <tr><th>Indirizzo</th><td><?= htmlspecialchars($contatto['indirizzo'] ?? '') ?></td></tr>
    </table>

    <form method="post" action="delete.php?id=<?= $id ?>">
        <input type="hidden" name="confirm" value="1">
        <button type="submit" class="btn-delete">
            Sì, elimina
        </button>
        <a href="index.php" class="btn-cancel">Annulla</a>
    </form>
</body>
</html>
