<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../../Controllers/ReservationC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();
$resaC = new ReservationC();
$users = $controller->getUtilisateurs();
$uid = filter_var($_POST['uid'] ?? $_GET['uid'] ?? null, FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['annuler_id']) && $uid) {
    $id = filter_var($_POST['annuler_id'], FILTER_VALIDATE_INT);
    $ok = $id && $resaC->annuler($id, $uid);
    header('Location: mes_reservations.php?uid=' . $uid . '&msg=' . ($ok ? 'resa_annulee' : 'refuse'));
    exit;
}
$reservations = $uid ? $resaC->getByPassager($uid) : [];
$pageTitle = 'Mes réservations'; $currentPage = 'trajets';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <?php require_once __DIR__ . '/../includes/mobilite_nav.php'; ?>

    <div class="container py-5" style="min-height: 60vh;">
        <h2 class="mb-4">Mes réservations (passager)</h2>
        <?= mobiliteFlash($_GET['msg'] ?? '') ?>
        <form method="GET" class="mb-4" style="max-width: 360px;"><label class="form-label">Je suis</label><?= mobiliteUserSelect($users, $uid, 'uid', true) ?></form>

        <?php if ($uid && empty($reservations)): ?><p>Aucune réservation. <a href="trajets.php">Rechercher un trajet</a></p><?php endif; ?>
        <?php if ($reservations): ?>
        <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark"><tr><th>Trajet</th><th>Départ le</th><th>Conducteur</th><th>Places</th><th>Total</th><th>Statut</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($reservations as $r): $active = $r['statut'] === 'confirmee'; ?>
                <tr>
                    <td><?= htmlspecialchars($r['depart']) ?> → <?= htmlspecialchars($r['arrivee']) ?></td>
                    <td><?= htmlspecialchars(mobiliteDate($r['date_depart'])) ?></td>
                    <td><?= htmlspecialchars($r['conducteur_nom']) ?></td>
                    <td><?= (int)$r['nb_places'] ?></td>
                    <td><?= number_format((float)$r['prix'] * (int)$r['nb_places'], 2, ',', ' ') ?> DT</td>
                    <td><span class="badge <?= $active ? 'bg-success' : 'bg-secondary' ?>"><?= $active ? 'Confirmée' : 'Annulée' ?></span></td>
                    <td>
                        <?php if ($active): ?>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="uid" value="<?= (int)$uid ?>">
                            <input type="hidden" name="annuler_id" value="<?= (int)$r['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Annuler cette réservation ?')">Annuler</button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
