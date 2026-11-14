<?php
require_once __DIR__ . '/../../Controllers/JournalActionC.php';
$controller = new JournalActionC();
$type = $_GET['type'] ?? '';
$actions = $controller->getActions($type);
$types = $controller->getTypes();
$currentPage = 'journal';
ob_start();
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Journal des actions</h2>
    <form method="GET" class="d-flex gap-2">
        <select name="type" class="form-select form-select-sm">
            <option value="">Toutes les actions</option>
            <?php foreach ($types as $t): ?>
                <option value="<?= htmlspecialchars($t) ?>" <?= $t === $type ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary btn-sm">Filtrer</button>
    </form>
</div>
<table class="table table-bordered bg-white">
    <thead class="table-dark"><tr><th>Date</th><th>Admin</th><th>Action</th><th>Description</th></tr></thead>
    <tbody>
        <?php if (empty($actions)): ?><tr><td colspan="4" class="text-center text-muted">Aucune action.</td></tr><?php endif; ?>
        <?php foreach ($actions as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['date_action']) ?></td>
            <td><?= htmlspecialchars($a['admin_nom']) ?></td>
            <td><span class="badge bg-secondary"><?= htmlspecialchars($a['type_action']) ?></span></td>
            <td><?= htmlspecialchars($a['description']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
