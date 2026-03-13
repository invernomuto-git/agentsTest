<?php
require_once __DIR__ . '/db.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome']     ?? '');
    $settore  = trim($_POST['settore']  ?? '');
    $indirizzo = trim($_POST['indirizzo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $email    = trim($_POST['email']    ?? '');
    $sito_web = trim($_POST['sito_web'] ?? '');

    if ($nome === '') {
        $errors[] = 'Il campo Nome è obbligatorio.';
    }

    if (empty($errors)) {
        try {
            $stmt = get_db()->prepare(
                'INSERT INTO aziende (nome, settore, indirizzo, telefono, email, sito_web)
                 VALUES (:nome, :settore, :indirizzo, :telefono, :email, :sito_web)'
            );
            $stmt->execute([
                ':nome'      => $nome,
                ':settore'   => $settore  !== '' ? $settore  : null,
                ':indirizzo' => $indirizzo !== '' ? $indirizzo : null,
                ':telefono'  => $telefono !== '' ? $telefono : null,
                ':email'     => $email    !== '' ? $email    : null,
                ':sito_web'  => $sito_web !== '' ? $sito_web : null,
            ]);
            $success = true;
        } catch (PDOException $e) {
            $errors[] = 'Errore durante il salvataggio dell\'azienda.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuova azienda – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>
    <nav><a href="aziende.php">← Lista aziende</a></nav>

    <h2>Nuova azienda</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Azienda aggiunta con successo. <a href="aziende.php">Torna alla lista</a>.</div>
    <?php endif; ?>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <form method="post" action="create_azienda.php">
        <div class="form-group">
            <label for="nome">Nome *</label>
            <input type="text" id="nome" name="nome"
                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="settore">Settore</label>
            <input type="text" id="settore" name="settore"
                   value="<?= htmlspecialchars($_POST['settore'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="indirizzo">Indirizzo</label>
            <input type="text" id="indirizzo" name="indirizzo"
                   value="<?= htmlspecialchars($_POST['indirizzo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="text" id="telefono" name="telefono"
                   value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="sito_web">Sito web</label>
            <input type="text" id="sito_web" name="sito_web"
                   value="<?= htmlspecialchars($_POST['sito_web'] ?? '') ?>">
        </div>
        <button type="submit" class="btn-primary">Salva azienda</button>
        <a href="aziende.php" class="btn-cancel">Annulla</a>
    </form>
</body>
</html>
