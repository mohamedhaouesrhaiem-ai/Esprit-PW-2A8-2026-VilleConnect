<h2>Gestion des administrateurs</h2>

<form method="POST" action="index.php?page=admins" class="d-flex gap-2 my-4 p-3 bg-white rounded shadow-sm">
    <label for="user_id" class="visually-hidden">Utilisateur</label>
    <select name="user_id" id="user_id" class="form-select" required>
        <option value="">Choisir un utilisateur…</option>
        <?php foreach ($data['candidats'] as $u) { ?>
            <option value="<?= $u['id']; ?>"><?= $u['nom']; ?> (<?= $u['email']; ?>)</option>
        <?php } ?>
    </select>
    <label for="niveau" class="visually-hidden">Niveau</label>
    <select name="niveau" id="niveau" class="form-select">
        <option value="moderateur">Modérateur</option>
        <option value="super_admin">Super admin</option>
    </select>
    <button type="submit" class="btn btn-success text-nowrap">+ Nommer admin</button>
</form>

<table class="table table-bordered bg-white">
    <thead class="table-dark">
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Niveau</th><th>Depuis</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php foreach ($data['admins'] as $a) { ?>
        <tr>
            <td><?= $a['id']; ?></td>
            <td><?= $a['nom']; ?></td>
            <td><?= $a['email']; ?></td>
            <td><?= $a['niveau']; ?></td>
            <td><?= $a['date_nomination']; ?></td>
            <td>
                <?php if ($a['id'] != ADMIN_COURANT) { ?>
                <form method="POST" action="index.php?page=admins">
                    <input type="hidden" name="delete_id" value="<?= $a['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Retirer</button>
                </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
