<?php
require_once __DIR__ . '/db.php';

$errors = [];
$success = false;

// Recupera l'ID dalla query string
if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = (int) $_GET['id'];

// Legge il contatto corrente
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome            = trim($_POST['nome']            ?? '');
    $cognome         = trim($_POST['cognome']         ?? '');
    $numero_telefono = trim($_POST['numero_telefono'] ?? '');
    $indirizzo       = trim($_POST['indirizzo']       ?? '');

    // Validazione
    if ($nome === '') {
        $errors[] = 'Il campo Nome è obbligatorio.';
    }
    if ($cognome === '') {
        $errors[] = 'Il campo Cognome è obbligatorio.';
    }
    if ($numero_telefono === '') {
        $errors[] = 'Il campo Numero di telefono è obbligatorio.';
    }

    if (empty($errors)) {
        try {
            $stmt = get_db()->prepare(
                'UPDATE contatti
                    SET nome = :nome,
                        cognome = :cognome,
                        numero_telefono = :numero_telefono,
                        indirizzo = :indirizzo
                  WHERE id = :id'
            );
            $stmt->execute([
                ':nome'            => $nome,
                ':cognome'         => $cognome,
                ':numero_telefono' => $numero_telefono,
                ':indirizzo'       => $indirizzo !== '' ? $indirizzo : null,
                ':id'              => $id,
            ]);
            // Aggiorna i dati locali per il form
            $contatto = array_merge($contatto, [
                'nome'            => $nome,
                'cognome'         => $cognome,
                'numero_telefono' => $numero_telefono,
                'indirizzo'       => $indirizzo,
            ]);
            $success = true;
        } catch (PDOException $e) {
            $errors[] = 'Errore durante l\'aggiornamento del contatto.';
        }
    }
}

// Valori per il form (POST ha priorità in caso di errori)
$v = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ['nome' => $_POST['nome'] ?? '', 'cognome' => $_POST['cognome'] ?? '',
       'numero_telefono' => $_POST['numero_telefono'] ?? '', 'indirizzo' => $_POST['indirizzo'] ?? '']
    : $contatto;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica contatto – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>
    <nav><a href="index.php">← Lista contatti</a></nav>

    <h2>Modifica contatto #<?= $id ?></h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Contatto aggiornato con successo. <a href="index.php">Torna alla lista</a>.</div>
    <?php endif; ?>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <form method="post" action="edit.php?id=<?= $id ?>">
        <div class="form-group">
            <label for="nome">Nome *</label>
            <input type="text" id="nome" name="nome"
                   value="<?= htmlspecialchars($v['nome']) ?>" required>
        </div>
        <div class="form-group">
            <label for="cognome">Cognome *</label>
            <input type="text" id="cognome" name="cognome"
                   value="<?= htmlspecialchars($v['cognome']) ?>" required>
        </div>
        <div class="form-group">
            <label for="numero_telefono">Numero di telefono *</label>
            <input type="text" id="numero_telefono" name="numero_telefono"
                   value="<?= htmlspecialchars($v['numero_telefono']) ?>" required>
        </div>
        <div class="form-group">
            <label for="indirizzo">Indirizzo</label>
            <input type="text" id="indirizzo" name="indirizzo"
                   value="<?= htmlspecialchars($v['indirizzo'] ?? '') ?>">
        </div>
        <button type="submit" class="btn-primary">Aggiorna contatto</button>
        <a href="index.php" class="btn-cancel">Annulla</a>
    </form>
</body>
</html>
