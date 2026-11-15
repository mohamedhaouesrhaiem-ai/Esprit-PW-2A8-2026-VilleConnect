<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();
$users = $controller->getUtilisateurs();
$uid = filter_var($_POST['uid'] ?? $_GET['uid'] ?? null, FILTER_VALIDATE_INT);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['annuler_id']) && $uid) {
    $id = filter_var($_POST['annuler_id'], FILTER_VALIDATE_INT);
    $ok = $id && $controller->annulerTrajet($id, $uid);
    header('Location: mes_trajets.php?uid=' . $uid . '&msg=' . ($ok ? 'annule' : 'refuse'));
    exit;
}
$trajets = $uid ? $controller->getByConducteur($uid) : [];
$pageTitle = 'Mes trajets'; $currentPage = 'trajets';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <?php require_once __DIR__ . '/../includes/mobilite_nav.php'; ?>

    <div class="container py-5" style="min-height: 60vh;">
        <h2 class="mb-4">Mes trajets (conducteur)</h2>
        <?= mobiliteFlash($_GET['msg'] ?? '') ?>
        <form method="GET" class="mb-4" style="max-width: 360px;"><label class="form-label">Je suis</label><?= mobiliteUserSelect($users, $uid, 'uid', true) ?></form>

        <?php if ($uid && empty($trajets)): ?><p>Vous n'avez publié aucun trajet. <a href="proposer_trajet.php">Proposer un trajet</a></p><?php endif; ?>
        <?php if ($trajets): ?>
        <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-dark"><tr><th>Trajet</th><th>Départ le</th><th>Prix</th><th>Places libres</th><th>Statut</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($trajets as $t): ?>
                <tr>
                    <td><?= htmlspecialchars($t['depart']) ?> → <?= htmlspecialchars($t['arrivee']) ?></td>
                    <td><?= htmlspecialchars(mobiliteDate($t['date_depart'])) ?></td>
                    <td><?= number_format((float)$t['prix'], 2, ',', ' ') ?> DT</td>
                    <td><?= (int)$t['places_restantes'] ?> / <?= (int)$t['places'] ?></td>
                    <td><span class="badge <?= $t['statut'] === 'actif' ? 'bg-success' : 'bg-secondary' ?>"><?= $t['statut'] === 'actif' ? 'Actif' : 'Annulé' ?></span></td>
                    <td>
                        <a href="reservations_trajet.php?id=<?= (int)$t['id'] ?>&uid=<?= (int)$uid ?>" class="btn btn-outline-primary btn-sm">Voir les réservations</a>
                        <?php if ($t['statut'] === 'actif'): ?>
                            <a href="modifier_trajet.php?id=<?= (int)$t['id'] ?>&uid=<?= (int)$uid ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="uid" value="<?= (int)$uid ?>">
                                <input type="hidden" name="annuler_id" value="<?= (int)$t['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Annuler ce trajet et ses réservations ?')">Annuler</button>
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
