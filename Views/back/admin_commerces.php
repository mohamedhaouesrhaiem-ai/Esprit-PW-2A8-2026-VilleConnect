<?php
require_once __DIR__ . '/../../Controllers/CommerceC.php';
$controller = new CommerceC();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $id = filter_var($_POST['delete_id'], FILTER_VALIDATE_INT);
    if ($id) { $controller->deleteCommerce($id); }
    header('Location: admin_commerces.php');
    exit;
}
$commerces = $controller->getCommerces();
$currentPage = 'commerces';
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestion des Commerces</h2>
    <a href="add_commerce.php" class="btn btn-success">+ Nouveau Commerce</a>
</div>
<table class="table table-bordered mt-4 bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Nom</th><th>Catégorie</th><th>Adresse</th><th>Téléphone</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($commerces as $c): ?>
        <tr>
            <td><?= (int)$c['id'] ?></td>
            <td><?= htmlspecialchars($c['nom']) ?></td>
            <td><?= htmlspecialchars($c['categorie_nom']) ?></td>
            <td><?= htmlspecialchars($c['adresse']) ?></td>
            <td><?= htmlspecialchars($c['telephone']) ?></td>
            <td>
                <a href="edit_commerce.php?id=<?= (int)$c['id'] ?>" class="btn btn-primary btn-sm">Modifier</a>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= (int)$c['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce commerce et ses offres ?')">Supprimer</button>
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
