<?php
require_once __DIR__ . '/../../Controllers/OffreC.php';
$controller = new OffreC();

$commerces = $controller->getCommerces();
$commerceIds = array_map('intval', array_column($commerces, 'id'));
$commerce_id = filter_var($_GET['commerce_id'] ?? '', FILTER_VALIDATE_INT);
$commerce_id = in_array($commerce_id, $commerceIds, true) ? $commerce_id : '';

$offres = $controller->getOffres($commerce_id, true);
$pageTitle = 'Offres Locales';
$currentPage = 'offres';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Offres Locales</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <form method="GET" class="row g-3 mb-5 p-4 bg-light rounded">
                <div class="col-md-9">
                    <select name="commerce_id" class="form-select">
                        <option value="">Tous les commerces</option>
                        <?php foreach ($commerces as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= $commerce_id === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Filtrer</button></div>
            </form>

            <div class="row g-4 justify-content-center">
                <?php if (empty($offres)): ?>
                    <div class="text-center"><p>Aucune offre en cours pour le moment.</p></div>
                <?php endif; ?>
                <?php foreach ($offres as $o): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="course-item bg-light">
                        <div class="text-center p-4 pb-0">
                            <h3 class="mb-2 text-primary">-<?= (int)$o['reduction'] ?>%</h3>
                            <h5 class="mb-2"><?= htmlspecialchars($o['titre']) ?></h5>
                            <p class="mb-3"><?= htmlspecialchars($o['description'] ?? '') ?></p>
                        </div>
                        <div class="d-flex border-top">
                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-store text-primary me-2"></i><?= htmlspecialchars($o['commerce_nom']) ?></small>
                            <small class="flex-fill text-center py-2"><i class="fa fa-calendar text-primary me-2"></i>Jusqu'au <?= date('d/m/Y', strtotime($o['date_fin'])) ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
