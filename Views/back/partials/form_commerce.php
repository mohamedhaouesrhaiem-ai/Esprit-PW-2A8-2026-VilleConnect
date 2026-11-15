<?php if ($errors): ?>
    <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
<?php endif; ?>
<form method="POST" class="p-4 bg-white rounded shadow-sm">
    <div class="mb-3"><label>Nom</label><input type="text" name="nom" class="form-control" maxlength="255" value="<?= htmlspecialchars($d['nom']) ?>" required></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control"><?= htmlspecialchars($d['description'] ?? '') ?></textarea></div>
    <div class="mb-3"><label>Adresse</label><input type="text" name="adresse" class="form-control" maxlength="255" value="<?= htmlspecialchars($d['adresse']) ?>" required></div>
    <div class="mb-3"><label>Téléphone</label><input type="text" name="telephone" class="form-control" maxlength="20" value="<?= htmlspecialchars($d['telephone']) ?>" required></div>
    <div class="mb-3">
        <label>Catégorie</label>
        <select name="categorie_id" class="form-control" required>
            <?php foreach ($categories as $c): ?>
                <option value="<?= (int)$c['id'] ?>" <?= (int)$d['categorie_id'] === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Enregistrer</button>
    <a href="admin_commerces.php" class="btn btn-secondary">Annuler</a>
</form>
