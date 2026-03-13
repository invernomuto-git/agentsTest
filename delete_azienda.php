<?php
require_once __DIR__ . '/db.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: aziende.php');
    exit;
}
$id = (int) $_GET['id'];

try {
    $stmt = get_db()->prepare('SELECT * FROM aziende WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $azienda = $stmt->fetch();
} catch (PDOException $e) {
    $azienda = null;
}

if (!$azienda) {
    header('Location: aziende.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = get_db()->prepare('DELETE FROM aziende WHERE id = :id');
        $stmt->execute([':id' => $id]);
        header('Location: aziende.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        header('Location: aziende.php?error=delete');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elimina azienda – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>
    <nav><a href="aziende.php">← Lista aziende</a></nav>

    <h2>Elimina azienda</h2>

    <p>Sei sicuro di voler eliminare l'azienda <strong><?= htmlspecialchars($azienda['nome']) ?></strong>?</p>
    <p>I contatti associati a questa azienda non verranno eliminati, ma perderanno l'associazione.</p>

    <form method="post" action="delete_azienda.php?id=<?= $id ?>">
        <button type="submit" class="btn-delete">Sì, elimina</button>
        <a href="aziende.php" class="btn-cancel">Annulla</a>
    </form>
</body>
</html>
