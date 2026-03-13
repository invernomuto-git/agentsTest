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

// Carica l'elenco delle aziende per il dropdown
try {
    $aziende = get_db()->query('SELECT id, nome FROM aziende ORDER BY nome')->fetchAll();
} catch (PDOException $e) {
    $aziende = [];
}

// Carica l'elenco dei tag disponibili
try {
    $tags_disponibili = get_db()->query('SELECT id, nome FROM tags ORDER BY id')->fetchAll();
} catch (PDOException $e) {
    $tags_disponibili = [];
}

// Carica i tag attualmente associati al contatto
try {
    $stmt_ct = get_db()->prepare('SELECT id_tag FROM contatti_tags WHERE id_contatto = :id');
    $stmt_ct->execute([':id' => $id]);
    $tag_ids_correnti = array_column($stmt_ct->fetchAll(), 'id_tag');
    $tag_ids_correnti = array_map('intval', $tag_ids_correnti);
} catch (PDOException $e) {
    $tag_ids_correnti = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome            = trim($_POST['nome']            ?? '');
    $cognome         = trim($_POST['cognome']         ?? '');
    $numero_telefono = trim($_POST['numero_telefono'] ?? '');
    $indirizzo       = trim($_POST['indirizzo']       ?? '');
    $data_nascita    = trim($_POST['data_nascita']    ?? '');
    $id_azienda      = isset($_POST['id_azienda']) && ctype_digit($_POST['id_azienda'])
                        ? (int) $_POST['id_azienda']
                        : null;

    // Recupera i tag selezionati e valida che siano ID numerici validi
    $tag_ids_validi = array_values(array_filter(
        array_map('intval', $_POST['tags'] ?? []),
        fn($v) => $v > 0
    ));

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
            $db = get_db();
            $db->beginTransaction();

            $stmt = $db->prepare(
                'UPDATE contatti
                    SET nome = :nome,
                        cognome = :cognome,
                        numero_telefono = :numero_telefono,
                        indirizzo = :indirizzo,
                        data_nascita = :data_nascita,
                        id_azienda = :id_azienda
                  WHERE id = :id'
            );
            $stmt->execute([
                ':nome'            => $nome,
                ':cognome'         => $cognome,
                ':numero_telefono' => $numero_telefono,
                ':indirizzo'       => $indirizzo !== '' ? $indirizzo : null,
                ':data_nascita'    => $data_nascita !== '' ? $data_nascita : null,
                ':id_azienda'      => $id_azienda,
                ':id'              => $id,
            ]);

            // Aggiorna le associazioni ai tag: elimina tutte e re-inserisce quelle selezionate
            $db->prepare('DELETE FROM contatti_tags WHERE id_contatto = :id')->execute([':id' => $id]);
            if (!empty($tag_ids_validi)) {
                $stmt_tag = $db->prepare(
                    'INSERT IGNORE INTO contatti_tags (id_contatto, id_tag) VALUES (:id_contatto, :id_tag)'
                );
                foreach ($tag_ids_validi as $id_tag) {
                    $stmt_tag->execute([':id_contatto' => $id, ':id_tag' => $id_tag]);
                }
            }

            $db->commit();

            // Aggiorna i dati locali per il form
            $contatto = array_merge($contatto, [
                'nome'            => $nome,
                'cognome'         => $cognome,
                'numero_telefono' => $numero_telefono,
                'indirizzo'       => $indirizzo,
                'data_nascita'    => $data_nascita,
                'id_azienda'      => $id_azienda,
            ]);
            $tag_ids_correnti = $tag_ids_validi;
            $success = true;
        } catch (PDOException $e) {
            isset($db) && $db->inTransaction() && $db->rollBack();
            $errors[] = 'Errore durante l\'aggiornamento del contatto.';
        }
    }
}

// Valori per il form (POST ha priorità in caso di errori)
$v = $_SERVER['REQUEST_METHOD'] === 'POST'
    ? ['nome' => $_POST['nome'] ?? '', 'cognome' => $_POST['cognome'] ?? '',
       'numero_telefono' => $_POST['numero_telefono'] ?? '', 'indirizzo' => $_POST['indirizzo'] ?? '',
       'data_nascita' => $_POST['data_nascita'] ?? '',
       'id_azienda' => isset($_POST['id_azienda']) && ctype_digit($_POST['id_azienda'])
                        ? (int)$_POST['id_azienda'] : null]
    : $contatto;

// Tag selezionati per il form (POST ha priorità in caso di errori)
$tag_ids_form = ($_SERVER['REQUEST_METHOD'] === 'POST' && !$success)
    ? array_values(array_filter(
        array_map('intval', $_POST['tags'] ?? []),
        fn($v) => $v > 0
      ))
    : $tag_ids_correnti;
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
        <div class="form-group">
            <label for="data_nascita">Data di nascita</label>
            <input type="date" id="data_nascita" name="data_nascita"
                   value="<?= htmlspecialchars($v['data_nascita'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="id_azienda">Azienda</label>
            <select id="id_azienda" name="id_azienda">
                <option value="">— Nessuna azienda —</option>
                <?php foreach ($aziende as $az): ?>
                    <option value="<?= $az['id'] ?>"
                        <?= (!empty($v['id_azienda']) && (int)$v['id_azienda'] === $az['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($az['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (!empty($tags_disponibili)): ?>
        <div class="form-group">
            <label>Tag</label>
            <div class="tag-checkboxes">
                <?php foreach ($tags_disponibili as $tag): ?>
                    <label class="tag-checkbox-label">
                        <input type="checkbox" name="tags[]"
                               value="<?= $tag['id'] ?>"
                               <?= in_array((int)$tag['id'], $tag_ids_form, true) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($tag['nome']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn-primary">Aggiorna contatto</button>
        <a href="index.php" class="btn-cancel">Annulla</a>
    </form>
</body>
</html>
