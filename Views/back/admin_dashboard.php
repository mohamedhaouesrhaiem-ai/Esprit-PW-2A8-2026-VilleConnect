<?php
require_once __DIR__ . '/../../Controllers/AdminC.php';
$controller = new AdminC();
$stats = $controller->getStatistiques();
$currentPage = 'dashboard';

function barre($total, $max) { return $max > 0 ? round($total * 100 / $max) : 0; }
ob_start();
?>
<h2 class="mb-4">Statistiques</h2>

<div class="row g-3 mb-4">
    <?php
    $cartes = [
        ['Utilisateurs', $stats['utilisateurs'], 'primary'],
        ['Utilisateurs bloqués', $stats['bloques'], 'danger'],
        ['Événements', $stats['evenements'], 'success'],
        ['Inscriptions', $stats['inscriptions'], 'info'],
        ['Signalements', $stats['signalements'], 'warning'],
        ['Actions admin', $stats['actions'], 'secondary'],
    ];
    foreach ($cartes as [$label, $valeur, $couleur]): ?>
    <div class="col-md-4 col-lg-2">
        <div class="card border-<?= $couleur ?> h-100">
            <div class="card-body text-center">
                <div class="text-muted small"><?= $label ?></div>
                <div class="fs-2 fw-bold text-<?= $couleur ?>"><?= $valeur ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-3">
    <?php foreach (['parStatut' => 'Signalements par statut', 'parCategorie' => 'Signalements par catégorie'] as $cle => $titre):
        $max = max(array_merge([0], array_column($stats[$cle], 'total'))); ?>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white fw-bold"><?= $titre ?></div>
            <div class="card-body">
                <?php if (empty($stats[$cle])): ?><p class="text-muted mb-0">Aucune donnée.</p><?php endif; ?>
                <?php foreach ($stats[$cle] as $ligne): ?>
                <div class="mb-2">
                    <div class="d-flex justify-content-between small">
                        <span><?= htmlspecialchars($ligne['nom']) ?></span><span><?= (int)$ligne['total'] ?></span>
                    </div>
                    <div class="progress"><div class="progress-bar" style="width: <?= barre($ligne['total'], $max) ?>%"></div></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
