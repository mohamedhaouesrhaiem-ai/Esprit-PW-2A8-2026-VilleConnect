<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../../Controllers/ReservationC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();
$resaC = new ReservationC();

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$uid = filter_var($_GET['uid'] ?? null, FILTER_VALIDATE_INT);
$trajet = $id ? $controller->getTrajet($id) : false;
$autorise = $trajet && $uid && (int)$trajet['conducteur_id'] === $uid;
$reservations = $autorise ? $resaC->getByTrajet($id) : [];
$pageTitle = 'Réservations du trajet'; $currentPage = 'trajets';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="container py-5" style="min-height: 60vh;">
        <?php if (!$autorise): ?>
            <div class="alert alert-warning">Trajet introuvable ou vous n'en êtes pas le conducteur. <a href="mes_trajets.php">Mes trajets</a></div>
        <?php else: ?>
            <h2 class="mb-1">Réservations</h2>
            <p class="text-muted"><?= htmlspecialchars($trajet['depart']) ?> → <?= htmlspecialchars($trajet['arrivee']) ?> · <?= htmlspecialchars(mobiliteDate($trajet['date_depart'])) ?> · <?= (int)$trajet['places_restantes'] ?>/<?= (int)$trajet['places'] ?> place(s) libre(s)</p>
            <?php if (empty($reservations)): ?><p>Aucune réservation pour le moment.</p><?php else: ?>
            <table class="table table-bordered">
                <thead class="table-dark"><tr><th>Passager</th><th>Places</th><th>Réservé le</th><th>Statut</th></tr></thead>
                <tbody>
                <?php foreach ($reservations as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['passager_nom']) ?></td>
                        <td><?= (int)$r['nb_places'] ?></td>
                        <td><?= htmlspecialchars(mobiliteDate($r['date_reservation'])) ?></td>
                        <td><span class="badge <?= $r['statut'] === 'confirmee' ? 'bg-success' : 'bg-secondary' ?>"><?= $r['statut'] === 'confirmee' ? 'Confirmée' : 'Annulée' ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
            <a href="mes_trajets.php?uid=<?= (int)$uid ?>" class="btn btn-outline-primary">← Mes trajets</a>
        <?php endif; ?>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
