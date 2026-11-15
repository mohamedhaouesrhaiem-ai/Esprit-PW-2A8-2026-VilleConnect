<?php
require_once __DIR__ . '/../../Controllers/OffreC.php';
$controller = new OffreC();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
    if ($id) { $controller->deleteOffre($id); }
    header('Location: admin_offres.php');
    exit;
}
$offres = $controller->getOffres();
$aujourdhui = date('Y-m-d');
$currentPage = 'offres';
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestion des Offres</h2>
    <a href="add_offre.php" class="btn btn-success">+ Nouvelle Offre</a>
</div>
<table class="table table-bordered mt-4 bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Commerce</th><th>Titre</th><th>Réduction</th><th>Période</th><th>Statut</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($offres as $o): ?>
        <tr>
            <td><?= (int)$o['id'] ?></td>
            <td><?= htmlspecialchars($o['commerce_nom']) ?></td>
            <td><?= htmlspecialchars($o['titre']) ?></td>
            <td>-<?= (int)$o['reduction'] ?>%</td>
            <td><?= date('d/m/Y', strtotime($o['date_debut'])) ?> → <?= date('d/m/Y', strtotime($o['date_fin'])) ?></td>
            <td>
                <?php if ($o['date_fin'] < $aujourdhui): ?>Expirée
                <?php elseif ($o['date_debut'] > $aujourdhui): ?>À venir
                <?php else: ?>En cours<?php endif; ?>
            </td>
            <td>
                <a href="edit_offre.php?id=<?= (int)$o['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= (int)$o['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette offre ?')">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
