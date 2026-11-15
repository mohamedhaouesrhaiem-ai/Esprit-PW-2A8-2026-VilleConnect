<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../../Controllers/ReservationC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();
$resaC = new ReservationC();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
    if ($id) { $controller->deleteTrajet($id); }
    header('Location: admin_trajets.php');
    exit;
}
$trajets = $controller->getAll();
$reservations = $resaC->getAll();
$currentPage = 'trajets';
ob_start();
?>
<h2>Gestion des Trajets</h2>
<table class="table table-bordered mt-4 bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Trajet</th><th>Départ le</th><th>Conducteur</th><th>Places libres</th><th>Prix</th><th>Statut</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($trajets as $t): ?>
        <tr>
            <td><?= (int)$t['id'] ?></td>
            <td><?= htmlspecialchars($t['depart']) ?> → <?= htmlspecialchars($t['arrivee']) ?></td>
            <td><?= htmlspecialchars(mobiliteDate($t['date_depart'])) ?></td>
            <td><?= htmlspecialchars($t['conducteur_nom']) ?></td>
            <td><?= (int)$t['places_restantes'] ?> / <?= (int)$t['places'] ?></td>
            <td><?= number_format((float)$t['prix'], 2, ',', ' ') ?> DT</td>
            <td><?= $t['statut'] === 'actif' ? 'Actif' : 'Annulé' ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= (int)$t['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce trajet et ses réservations ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h3 class="mt-5">Réservations</h3>
<table class="table table-bordered mt-3 bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Trajet</th><th>Passager</th><th>Places</th><th>Réservé le</th><th>Statut</th></tr></thead>
    <tbody>
        <?php foreach ($reservations as $r): ?>
        <tr>
            <td><?= (int)$r['id'] ?></td>
            <td><?= htmlspecialchars($r['depart']) ?> → <?= htmlspecialchars($r['arrivee']) ?> (<?= htmlspecialchars(mobiliteDate($r['date_depart'])) ?>)</td>
            <td><?= htmlspecialchars($r['passager_nom']) ?></td>
            <td><?= (int)$r['nb_places'] ?></td>
            <td><?= htmlspecialchars(mobiliteDate($r['date_reservation'])) ?></td>
            <td><?= $r['statut'] === 'confirmee' ? 'Confirmée' : 'Annulée' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
