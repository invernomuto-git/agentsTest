<?php
require_once __DIR__ . '/db.php';

$errors = [];
$success = false;

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
    $nome      = trim($_POST['nome']      ?? '');
    $settore   = trim($_POST['settore']   ?? '');
    $indirizzo = trim($_POST['indirizzo'] ?? '');
    $telefono  = trim($_POST['telefono']  ?? '');
    $email     = trim($_POST['email']     ?? '');
    $sito_web  = trim($_POST['sito_web']  ?? '');

    if ($nome === '') {
        $errors[] = 'Il campo Nome è obbligatorio.';
    }

    if (empty($errors)) {
        try {
            $stmt = get_db()->prepare(
                'UPDATE aziende
                    SET nome = :nome,
                        settore = :settore,
                        indirizzo = :indirizzo,
                        telefono = :telefono,
                        email = :email,
                        sito_web = :sito_web
                  WHERE id = :id'
            );
            $stmt->execute([
                ':nome'      => $nome,
                ':settore'   => $settore   !== '' ? $settore   : null,
                ':indirizzo' => $indirizzo !== '' ? $indirizzo : null,
                ':telefono'  => $telefono  !== '' ? $telefono  : null,
                ':email'     => $email     !== '' ? $email     : null,
                ':sito_web'  => $sito_web  !== '' ? $sito_web  : null,
                ':id'        => $id,
            ]);
            $azienda = array_merge($azienda, [
                'nome'      => $nome,
                'settore'   => $settore,
                'indirizzo' => $indirizzo,
                'telefono'  => $telefono,
                'email'     => $email,
                'sito_web'  => $sito_web,
            ]);
            $success = true;
        } catch (PDOException $e) {
            $errors[] = 'Errore durante l\'aggiornamento dell\'azienda.';
        }
    }
}

$v = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ['nome' => $_POST['nome'] ?? '', 'settore' => $_POST['settore'] ?? '',
       'indirizzo' => $_POST['indirizzo'] ?? '', 'telefono' => $_POST['telefono'] ?? '',
       'email' => $_POST['email'] ?? '', 'sito_web' => $_POST['sito_web'] ?? '']
    : $azienda;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica azienda – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>
    <nav><a href="aziende.php">← Lista aziende</a></nav>

    <h2>Modifica azienda #<?= $id ?></h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Azienda aggiornata con successo. <a href="aziende.php">Torna alla lista</a>.</div>
    <?php endif; ?>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <form method="post" action="edit_azienda.php?id=<?= $id ?>">
        <div class="form-group">
            <label for="nome">Nome *</label>
            <input type="text" id="nome" name="nome"
                   value="<?= htmlspecialchars($v['nome']) ?>" required>
        </div>
        <div class="form-group">
            <label for="settore">Settore</label>
            <input type="text" id="settore" name="settore"
                   value="<?= htmlspecialchars($v['settore'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="indirizzo">Indirizzo</label>
            <input type="text" id="indirizzo" name="indirizzo"
                   value="<?= htmlspecialchars($v['indirizzo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="telefono">Telefono</label>
            <input type="text" id="telefono" name="telefono"
                   value="<?= htmlspecialchars($v['telefono'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($v['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="sito_web">Sito web</label>
            <input type="text" id="sito_web" name="sito_web"
                   value="<?= htmlspecialchars($v['sito_web'] ?? '') ?>">
        </div>
        <button type="submit" class="btn-primary">Aggiorna azienda</button>
        <a href="aziende.php" class="btn-cancel">Annulla</a>
    </form>
</body>
</html>
