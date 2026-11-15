<?php
require_once __DIR__ . '/../../Controllers/SignalementC.php';
require_once __DIR__ . '/../../Models/Signalement.php';
$controller = new SignalementC();

$categories = $controller->getCategories();
$categorieIds = array_map('intval', array_column($categories, 'id'));
$errors = [];
$titre = $description = '';
$categorie_id = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categorie_id = filter_var($_POST['categorie_id'] ?? null, FILTER_VALIDATE_INT);
    if (mb_strlen($titre) < 3 || mb_strlen($titre) > 255) { $errors[] = "Le titre doit contenir entre 3 et 255 caractères."; }
    if (mb_strlen($description) < 10) { $errors[] = "La description doit contenir au moins 10 caractères."; }
    if (!in_array($categorie_id, $categorieIds, true)) { $errors[] = "Catégorie invalide."; }
    if (!$errors) {
        // statut 1 = « En attente »
        $controller->addSignalement(new Signalement($titre, $description, $categorie_id, 1));
        header('Location: signalements.php');
        exit;
    }
}

$signalements = $controller->getSignalements();
$pageTitle = 'Signalements Urbains';
$currentPage = 'signalements';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container py-5">
        <h2 class="mb-4">Signaler un problème</h2>
        <?php if ($errors): ?>
            <div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div>
        <?php endif; ?>
        <form method="POST" class="mb-5 p-4 bg-light rounded">
            <div class="mb-3"><input type="text" name="titre" class="form-control" placeholder="Titre du problème" maxlength="255" value="<?= htmlspecialchars($titre) ?>" required></div>
            <div class="mb-3"><textarea name="description" class="form-control" placeholder="Description" required><?= htmlspecialchars($description) ?></textarea></div>
            <div class="mb-3">
                <select name="categorie_id" class="form-control" required>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= $categorie_id === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer le signalement</button>
        </form>

        <h2 class="mb-4">Liste des signalements</h2>
        <div class="row">
            <?php foreach($signalements as $s): ?>
            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h4><?= htmlspecialchars($s['titre']) ?></h4>
                    <p><?= htmlspecialchars($s['description']) ?></p>
                    <span class="badge bg-secondary"><?= htmlspecialchars($s['categorie_nom']) ?></span>
                    <span class="badge bg-warning text-dark"><?= htmlspecialchars($s['statut_nom']) ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
