<?php
require_once __DIR__ . '/../../Controllers/AdminC.php';
$controller = new AdminC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['user_id'], $_POST['statut'])) {
        $controller->changerStatutUtilisateur($_POST['user_id'], $_POST['statut']);
    } elseif (isset($_POST['delete_signalement'])) {
        $controller->supprimerSignalement($_POST['delete_signalement']);
    }
    header('Location: admin_moderation.php');
    exit;
}

$utilisateurs = $controller->getUtilisateurs();
$signalements = $controller->getSignalements();
$currentPage = 'moderation';
ob_start();
?>
<h2>Modération</h2>

<h4 class="mt-4">Utilisateurs</h4>
<table class="table table-bordered bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Nom</th><th>Email</th><th>Statut</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($utilisateurs as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['id']) ?></td>
            <td><?= htmlspecialchars($u['nom']) ?> <?= $u['admin_id'] ? '<span class="badge bg-primary">admin</span>' : '' ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="badge <?= $u['statut'] === 'bloque' ? 'bg-danger' : 'bg-success' ?>"><?= htmlspecialchars($u['statut']) ?></span></td>
            <td>
                <?php if (!$u['admin_id']): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?= htmlspecialchars($u['id']) ?>">
                    <?php if ($u['statut'] === 'bloque'): ?>
                        <input type="hidden" name="statut" value="actif">
                        <button class="btn btn-success btn-sm">Débloquer</button>
                    <?php else: ?>
                        <input type="hidden" name="statut" value="bloque">
                        <button class="btn btn-warning btn-sm" onclick="return confirm('Bloquer cet utilisateur ?')">Bloquer</button>
                    <?php endif; ?>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h4 class="mt-5">Signalements publiés</h4>
<table class="table table-bordered bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Titre</th><th>Description</th><th>Statut</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($signalements as $s): ?>
        <tr>
            <td><?= htmlspecialchars($s['id']) ?></td>
            <td><?= htmlspecialchars($s['titre']) ?></td>
            <td><?= htmlspecialchars($s['description']) ?></td>
            <td><?= htmlspecialchars($s['statut_nom']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_signalement" value="<?= htmlspecialchars($s['id']) ?>">
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Supprimer ce contenu inapproprié ?')">Supprimer</button>
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
