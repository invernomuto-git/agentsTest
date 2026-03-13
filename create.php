<?php
require_once __DIR__ . '/db.php';

$errors = [];
$success = false;

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
                'INSERT INTO contatti (nome, cognome, numero_telefono, indirizzo, data_nascita, id_azienda)
                 VALUES (:nome, :cognome, :numero_telefono, :indirizzo, :data_nascita, :id_azienda)'
            );
            $stmt->execute([
                ':nome'            => $nome,
                ':cognome'         => $cognome,
                ':numero_telefono' => $numero_telefono,
                ':indirizzo'       => $indirizzo !== '' ? $indirizzo : null,
                ':data_nascita'    => $data_nascita !== '' ? $data_nascita : null,
                ':id_azienda'      => $id_azienda,
            ]);
            $new_id = (int) $db->lastInsertId();

            // Inserisce le associazioni ai tag
            if (!empty($tag_ids_validi)) {
                $stmt_tag = $db->prepare(
                    'INSERT IGNORE INTO contatti_tags (id_contatto, id_tag) VALUES (:id_contatto, :id_tag)'
                );
                foreach ($tag_ids_validi as $id_tag) {
                    $stmt_tag->execute([':id_contatto' => $new_id, ':id_tag' => $id_tag]);
                }
            }

            $db->commit();
            $success = true;
        } catch (PDOException $e) {
            isset($db) && $db->inTransaction() && $db->rollBack();
            $errors[] = 'Errore durante il salvataggio del contatto.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuovo contatto – <?= APP_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1><?= APP_NAME ?></h1>
    <nav><a href="index.php">← Lista contatti</a></nav>

    <h2>Nuovo contatto</h2>

    <?php if ($success): ?>
        <div class="alert alert-success">Contatto aggiunto con successo. <a href="index.php">Torna alla lista</a>.</div>
    <?php endif; ?>

    <?php foreach ($errors as $err): ?>
        <div class="alert alert-error"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>

    <form method="post" action="create.php">
        <div class="form-group">
            <label for="nome">Nome *</label>
            <input type="text" id="nome" name="nome"
                   value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="cognome">Cognome *</label>
            <input type="text" id="cognome" name="cognome"
                   value="<?= htmlspecialchars($_POST['cognome'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="numero_telefono">Numero di telefono *</label>
            <input type="text" id="numero_telefono" name="numero_telefono"
                   value="<?= htmlspecialchars($_POST['numero_telefono'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="indirizzo">Indirizzo</label>
            <input type="text" id="indirizzo" name="indirizzo"
                   value="<?= htmlspecialchars($_POST['indirizzo'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="data_nascita">Data di nascita</label>
            <input type="date" id="data_nascita" name="data_nascita"
                   value="<?= htmlspecialchars($_POST['data_nascita'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="id_azienda">Azienda</label>
            <select id="id_azienda" name="id_azienda">
                <option value="">— Nessuna azienda —</option>
                <?php foreach ($aziende as $az): ?>
                    <option value="<?= $az['id'] ?>"
                        <?= (isset($_POST['id_azienda']) && (int)$_POST['id_azienda'] === $az['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($az['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (!empty($tags_disponibili)): ?>
        <div class="form-group">
            <label>Tag</label>
            <div class="tag-checkboxes">
                <?php
                $selected_tags = array_values(array_filter(
                    array_map('intval', $_POST['tags'] ?? []),
                    fn($v) => $v > 0
                ));
                foreach ($tags_disponibili as $tag):
                ?>
                    <label class="tag-checkbox-label">
                        <input type="checkbox" name="tags[]"
                               value="<?= $tag['id'] ?>"
                               <?= in_array((int)$tag['id'], $selected_tags, true) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($tag['nome']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
        <button type="submit" class="btn-primary">Salva contatto</button>
        <a href="index.php" class="btn-cancel">Annulla</a>
    </form>
</body>
</html>
