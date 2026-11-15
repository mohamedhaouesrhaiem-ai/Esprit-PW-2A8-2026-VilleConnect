<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Journal des actions</h2>
    <form method="GET" action="index.php" class="d-flex gap-2">
        <input type="hidden" name="page" value="journal">
        <label for="type" class="visually-hidden">Type d'action</label>
        <select name="type" id="type" class="form-select form-select-sm">
            <option value="">Toutes les actions</option>
            <?php foreach ($data['types'] as $t) { ?>
                <?php if ($t == $data['type']) { ?>
                    <option value="<?= $t; ?>" selected><?= $t; ?></option>
                <?php } else { ?>
                    <option value="<?= $t; ?>"><?= $t; ?></option>
                <?php } ?>
            <?php } ?>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
    </form>
</div>

<table class="table table-bordered bg-white">
    <thead class="table-dark">
        <tr><th>Date</th><th>Admin</th><th>Action</th><th>Description</th></tr>
    </thead>
    <tbody>
        <?php if (empty($data['actions'])) { ?>
        <tr><td colspan="4" class="text-center text-muted">Aucune action.</td></tr>
        <?php } ?>
        <?php foreach ($data['actions'] as $a) { ?>
        <tr>
            <td><?= $a['date_action']; ?></td>
            <td><?= $a['admin_nom']; ?></td>
            <td><span class="badge bg-secondary"><?= $a['type_action']; ?></span></td>
            <td><?= $a['description']; ?></td>
        </tr>
        <?php } ?>
    </tbody>
</table>
