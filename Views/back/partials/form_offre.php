<?php if ($errors): ?>
    <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>
<form method="POST" class="p-4 bg-white rounded shadow-sm">
    <div class="mb-3">
        <label>Commerce</label>
        <select name="commerce_id" class="form-control" required>
            <?php foreach ($commerces as $c): ?>
                <option value="<?= (int)$c['id'] ?>" <?= (int)$d['commerce_id'] === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3"><label>Titre</label><input type="text" name="titre" class="form-control" maxlength="255" value="<?= htmlspecialchars($d['titre']) ?>" required></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control"><?= htmlspecialchars($d['description'] ?? '') ?></textarea></div>
    <div class="mb-3"><label>Réduction (%)</label><input type="number" name="reduction" class="form-control" min="1" max="100" value="<?= htmlspecialchars((string)$d['reduction']) ?>" required></div>
    <div class="mb-3"><label>Date de début</label><input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($d['date_debut']) ?>" required></div>
    <div class="mb-3"><label>Date de fin</label><input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($d['date_fin']) ?>" required></div>
    <button type="submit" class="btn btn-success">Enregistrer</button>
    <a href="admin_offres.php" class="btn btn-secondary">Annuler</a>
</form>
