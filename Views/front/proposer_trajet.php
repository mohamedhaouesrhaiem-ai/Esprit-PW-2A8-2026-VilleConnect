<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();
$users = $controller->getUtilisateurs();

$errors = [];
$in = ['conducteur_id' => '', 'depart' => '', 'arrivee' => '', 'date_depart' => '', 'places' => 3, 'prix' => ''];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $in = array_merge($in, array_intersect_key($_POST, $in));
    [$errors, $d] = $controller->validate($_POST);
    if (!$errors) {
        $ok = $controller->addTrajet(new Trajet($d['conducteur_id'], $d['depart'], $d['arrivee'], $d['date_depart'], $d['places'], $d['prix']));
        if ($ok) { header('Location: mes_trajets.php?uid=' . (int)$d['conducteur_id'] . '&msg=cree'); exit; }
        $errors[] = "Erreur lors de l'enregistrement.";
    }
}
$pageTitle = 'Proposer un trajet'; $currentPage = 'trajets';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <?php require_once __DIR__ . '/../includes/mobilite_nav.php'; ?>

    <div class="container py-5" style="min-height: 60vh;">
        <div class="row justify-content-center"><div class="col-md-6">
            <h2 class="text-center mb-4">Proposer un trajet</h2>
            <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
            <form method="POST" action="proposer_trajet.php">
                <div class="mb-3"><label class="form-label">Conducteur</label><?= mobiliteUserSelect($users, $in['conducteur_id'], 'conducteur_id') ?></div>
                <div class="mb-3"><label class="form-label">Départ</label><?= mobiliteLieuSelect($in['depart'], 'depart') ?></div>
                <div class="mb-3"><label class="form-label">Arrivée</label><?= mobiliteLieuSelect($in['arrivee'], 'arrivee') ?></div>
                <div class="mb-3"><label class="form-label">Date et heure de départ</label><input type="datetime-local" name="date_depart" class="form-control" value="<?= htmlspecialchars($in['date_depart']) ?>" required></div>
                <div class="row">
                    <div class="col-6 mb-3"><label class="form-label">Places</label><input type="number" name="places" class="form-control" min="1" max="8" value="<?= htmlspecialchars($in['places']) ?>" required></div>
                    <div class="col-6 mb-3"><label class="form-label">Prix par place (DT)</label><input type="text" name="prix" class="form-control" value="<?= htmlspecialchars($in['prix']) ?>" required></div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Publier le trajet</button>
            </form>
        </div></div>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
