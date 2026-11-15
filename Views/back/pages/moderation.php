<h2>Modération</h2>

<h3 class="mt-4 fs-4">Utilisateurs</h3>
<table class="table table-bordered bg-white">
    <thead class="table-dark">
        <tr><th>ID</th><th>Nom</th><th>Email</th><th>Statut</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php foreach ($data['utilisateurs'] as $u) { ?>
        <tr>
            <td><?= $u['id']; ?></td>
            <td>
                <?= $u['nom']; ?>
                <?php if (in_array($u['id'], $data['adminsIds'])) { ?>
                    <span class="badge bg-primary">admin</span>
                <?php } ?>
            </td>
            <td><?= $u['email']; ?></td>
            <td>
                <?php if ($u['statut'] == 'bloque') { ?>
                    <span class="badge bg-danger">bloqué</span>
                <?php } else { ?>
                    <span class="badge bg-success">actif</span>
                <?php } ?>
            </td>
            <td>
                <?php if (!in_array($u['id'], $data['adminsIds'])) { ?>
                <form method="POST" action="index.php?page=moderation">
                    <input type="hidden" name="user_id" value="<?= $u['id']; ?>">
                    <?php if ($u['statut'] == 'bloque') { ?>
                        <input type="hidden" name="statut" value="actif">
                        <button type="submit" class="btn btn-success btn-sm">Débloquer</button>
                    <?php } else { ?>
                        <input type="hidden" name="statut" value="bloque">
                        <button type="submit" class="btn btn-warning btn-sm">Bloquer</button>
                    <?php } ?>
                </form>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>

<h3 class="mt-5 fs-4">Signalements publiés</h3>
<table class="table table-bordered bg-white">
    <thead class="table-dark">
        <tr><th>ID</th><th>Titre</th><th>Description</th><th>Statut</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php foreach ($data['signalements'] as $s) { ?>
        <tr>
            <td><?= $s['id']; ?></td>
            <td><?= $s['titre']; ?></td>
            <td><?= $s['description']; ?></td>
            <td><?= $s['statut_nom']; ?></td>
            <td>
                <form method="POST" action="index.php?page=moderation">
                    <input type="hidden" name="delete_signalement" value="<?= $s['id']; ?>">
                    <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php } ?>
    </tbody>
</table>
