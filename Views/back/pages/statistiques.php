<h2 class="mb-4">Statistiques</h2>

<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="card border-primary h-100"><div class="card-body text-center">
            <p class="text-muted small">Utilisateurs</p>
            <p class="fs-2 fw-bold text-primary"><?= $data['stats']['utilisateurs']; ?></p>
        </div></div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-danger h-100"><div class="card-body text-center">
            <p class="text-muted small">Utilisateurs bloqués</p>
            <p class="fs-2 fw-bold text-danger"><?= $data['stats']['bloques']; ?></p>
        </div></div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-success h-100"><div class="card-body text-center">
            <p class="text-muted small">Événements</p>
            <p class="fs-2 fw-bold text-success"><?= $data['stats']['evenements']; ?></p>
        </div></div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-info h-100"><div class="card-body text-center">
            <p class="text-muted small">Inscriptions</p>
            <p class="fs-2 fw-bold text-info"><?= $data['stats']['inscriptions']; ?></p>
        </div></div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-warning h-100"><div class="card-body text-center">
            <p class="text-muted small">Signalements</p>
            <p class="fs-2 fw-bold text-warning"><?= $data['stats']['signalements']; ?></p>
        </div></div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card border-secondary h-100"><div class="card-body text-center">
            <p class="text-muted small">Actions admin</p>
            <p class="fs-2 fw-bold text-secondary"><?= $data['stats']['actions']; ?></p>
        </div></div>
    </div>
</div>

<div class="row g-3">
    <section class="col-md-6">
        <div class="card h-100">
            <h3 class="card-header bg-white fs-6 fw-bold">Signalements par statut</h3>
            <div class="card-body">
                <?php foreach ($data['stats']['parStatut'] as $ligne) { ?>
                <div class="mb-2">
                    <p class="d-flex justify-content-between small mb-1">
                        <span><?= $ligne['nom']; ?></span>
                        <span><?= $ligne['total']; ?></span>
                    </p>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= $ligne['pourcentage']; ?>%"></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <section class="col-md-6">
        <div class="card h-100">
            <h3 class="card-header bg-white fs-6 fw-bold">Signalements par catégorie</h3>
            <div class="card-body">
                <?php foreach ($data['stats']['parCategorie'] as $ligne) { ?>
                <div class="mb-2">
                    <p class="d-flex justify-content-between small mb-1">
                        <span><?= $ligne['nom']; ?></span>
                        <span><?= $ligne['total']; ?></span>
                    </p>
                    <div class="progress">
                        <div class="progress-bar" style="width: <?= $ligne['pourcentage']; ?>%"></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
