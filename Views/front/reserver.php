<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../../Controllers/ReservationC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();
$resaC = new ReservationC();
$users = $controller->getUtilisateurs();

$id = filter_var($_POST['trajet_id'] ?? $_GET['id'] ?? null, FILTER_VALIDATE_INT);
$trajet = $id ? $controller->getTrajet($id) : false;
$dispo = $trajet && $trajet['statut'] === 'actif' && strtotime($trajet['date_depart']) > time();

$errors = [];
$passager = null; $nb = 1;
if ($dispo && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $passager = filter_var($_POST['passager_id'] ?? null, FILTER_VALIDATE_INT);
    $nb = filter_var($_POST['nb_places'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 8]]);
    if (!in_array($passager, array_map('intval', array_column($users, 'id')), true)) { $errors[] = "Veuillez choisir un passager."; }
    if ($nb === false) { $errors[] = "Le nombre de places doit être compris entre 1 et 8."; }
    if (!$errors) {
        [$ok, $err] = $resaC->reserver(new Reservation($id, $passager, $nb));
        if ($ok) { header('Location: mes_reservations.php?uid=' . $passager . '&msg=reserve'); exit; }
        $errors[] = $err;
    }
}
$pageTitle = 'Réserver une place'; $currentPage = 'trajets';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="container py-5" style="min-height: 60vh;">
        <div class="row justify-content-center"><div class="col-md-6">
            <h2 class="text-center mb-4">Réserver une place</h2>
            <?php if (!$dispo): ?>
                <div class="alert alert-warning">Ce trajet est introuvable ou n'est plus disponible. <a href="trajets.php">Voir les trajets</a></div>
            <?php else: ?>
                <p class="text-center text-muted"><?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?><br>
                    <?= htmlspecialchars(mobiliteDate($trajet['date_depart'])) ?> · <?= number_format((float)$trajet['prix'], 2, ',', ' ') ?> DT / place · <?= (int)$trajet['places_restantes'] ?> place(s) libre(s)</p>
                <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
                <form method="POST" action="reserver.php?id=<?= (int)$id ?>">
                    <input type="hidden" name="trajet_id" value="<?= (int)$id ?>">
                    <div class="mb-3"><label class="form-label">Passager</label><?= mobiliteUserSelect($users, $passager, 'passager_id') ?></div>
                    <div class="mb-3"><label class="form-label">Nombre de places</label><input type="number" name="nb_places" class="form-control" min="1" max="8" value="<?= (int)$nb ?>" required></div>
                    <button type="submit" class="btn btn-primary w-100">Confirmer la réservation</button>
                </form>
            <?php endif; ?>
        </div></div>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
