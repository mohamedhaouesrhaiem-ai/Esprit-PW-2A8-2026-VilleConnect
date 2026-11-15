<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();

$depart = trim($_GET['depart'] ?? '');
$arrivee = trim($_GET['arrivee'] ?? '');
$date = trim($_GET['date'] ?? '');
$errors = [];
if ($date !== '') {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if (!$d || $d->format('Y-m-d') !== $date) { $errors[] = "Date invalide."; $date = ''; }
}
foreach (['depart', 'arrivee'] as $champ) {
    if ($$champ !== '' && !in_array($$champ, TrajetC::LIEUX, true)) { $errors[] = "Lieu invalide."; $$champ = ''; }
}
$trajets = $errors ? [] : $controller->rechercher($depart, $arrivee, $date);
$pageTitle = 'Trajets'; $currentPage = 'trajets';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <?php require_once __DIR__ . '/../includes/mobilite_nav.php'; ?>

    <div class="container py-5">
        
    
        <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
        <form method="GET" class="row g-3 mb-5 p-4 bg-light rounded">
            <div class="col-md-3"><?= mobiliteLieuSelect($depart, 'depart', true) ?></div>
            <div class="col-md-3"><?= mobiliteLieuSelect($arrivee, 'arrivee', true) ?></div>
            <div class="col-md-3"><input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>"></div>
            <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Rechercher</button></div>
        </form>

        <?php if (empty($trajets) && !$errors): ?><p class="text-center">Aucun trajet disponible pour cette recherche.</p><?php endif; ?>
        <div class="row g-4">
            <?php foreach ($trajets as $t): ?>
            <div class="col-lg-4 col-md-6">
                <div class="card p-3 h-100">
                    <h5><?= htmlspecialchars($t['depart']) ?> → <?= htmlspecialchars($t['arrivee']) ?></h5>
                    <p class="mb-1"><i class="fa fa-calendar text-primary me-2"></i><?= htmlspecialchars(mobiliteDate($t['date_depart'])) ?></p>
                    <p class="mb-1"><i class="fa fa-user text-primary me-2"></i>Conducteur : <?= htmlspecialchars($t['conducteur_nom']) ?></p>
                    <p class="mb-1"><i class="fa fa-money-bill text-primary me-2"></i><?= number_format((float)$t['prix'], 2, ',', ' ') ?> DT / place</p>
                    <p class="mb-3"><span class="badge <?= $t['places_restantes'] > 0 ? 'bg-success' : 'bg-danger' ?>"><?= (int)$t['places_restantes'] ?> place(s) libre(s)</span></p>
                    <?php if ($t['places_restantes'] > 0): ?>
                        <a href="reserver.php?id=<?= (int)$t['id'] ?>" class="btn btn-primary mt-auto">Réserver une place</a>
                    <?php else: ?>
                        <button class="btn btn-secondary mt-auto" disabled>Complet</button>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
