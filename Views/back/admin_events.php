<?php
require_once __DIR__ . '/../../Controllers/EvenementC.php';
$controller = new EvenementC();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $controller->deleteEvenement($_POST['delete_id']);
    header('Location: admin_events.php');
    exit;
}
$events = $controller->getEvenements();
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Gestion des Événements</h2>
    <a href="add_event.php" class="btn btn-success">+ Nouvel Événement</a>
</div>
<table class="table table-bordered mt-4 bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Titre</th><th>Date</th><th>Lieu</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($events as $e): ?>
        <tr>
            <td><?= htmlspecialchars($e['id']) ?></td>
            <td><?= htmlspecialchars($e['titre']) ?></td>
            <td><?= htmlspecialchars($e['date_evenement']) ?></td>
            <td><?= htmlspecialchars($e['lieu_nom']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($e['id']) ?>">
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ?')">Supprimer</button>
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
