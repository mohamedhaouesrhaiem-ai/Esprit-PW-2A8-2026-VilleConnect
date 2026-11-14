<?php
require_once __DIR__ . '/../../Controllers/AdminC.php';
require_once __DIR__ . '/../../Models/Admin.php';
$controller = new AdminC();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete_id'])) {
        $controller->deleteAdmin($_POST['delete_id']);
    } elseif (isset($_POST['user_id'])) {
        $controller->addAdmin(new Admin($_POST['user_id'], $_POST['niveau'] ?? 'moderateur'));
    }
    header('Location: admin_admins.php');
    exit;
}

$admins = $controller->getAdmins();
$candidats = array_filter($controller->getUtilisateurs(), fn($u) => !$u['admin_id']);
$currentPage = 'admins';
ob_start();
?>
<h2>Gestion des administrateurs</h2>

<form method="POST" class="d-flex gap-2 my-4 p-3 bg-white rounded shadow-sm">
    <select name="user_id" class="form-select" required>
        <option value="">Choisir un utilisateur…</option>
        <?php foreach ($candidats as $u): ?>
            <option value="<?= htmlspecialchars($u['id']) ?>"><?= htmlspecialchars($u['nom'] . ' (' . $u['email'] . ')') ?></option>
        <?php endforeach; ?>
    </select>
    <select name="niveau" class="form-select">
        <option value="moderateur">Modérateur</option>
        <option value="super_admin">Super admin</option>
    </select>
    <button class="btn btn-success text-nowrap">+ Nommer admin</button>
</form>

<table class="table table-bordered bg-white">
    <thead class="table-dark"><tr><th>ID</th><th>Nom</th><th>Email</th><th>Niveau</th><th>Depuis</th><th>Action</th></tr></thead>
    <tbody>
        <?php foreach ($admins as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['id']) ?></td>
            <td><?= htmlspecialchars($a['nom']) ?></td>
            <td><?= htmlspecialchars($a['email']) ?></td>
            <td><?= htmlspecialchars($a['niveau']) ?></td>
            <td><?= htmlspecialchars($a['date_nomination']) ?></td>
            <td>
                <?php if ((int)$a['id'] !== JournalActionC::ADMIN_COURANT): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="delete_id" value="<?= htmlspecialchars($a['id']) ?>">
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Retirer cet admin ?')">Retirer</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
