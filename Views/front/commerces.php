<?php
require_once __DIR__ . '/../../Controllers/CommerceC.php';
$controller = new CommerceC();

$categories = $controller->getCategories();
$categorieIds = array_map('intval', array_column($categories, 'id'));
$categorie_id = filter_var($_GET['categorie_id'] ?? '', FILTER_VALIDATE_INT);
$categorie_id = in_array($categorie_id, $categorieIds, true) ? $categorie_id : '';
$recherche = trim($_GET['q'] ?? '');

$commerces = $controller->getCommerces($categorie_id, $recherche);
$pageTitle = 'Commerces Locaux';
$currentPage = 'commerces';
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
                    <h1 class="display-3 text-white animated slideInDown">Commerces Locaux</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <form method="GET" class="row g-3 mb-5 p-4 bg-light rounded">
                <div class="col-md-5"><input type="text" name="q" class="form-control" placeholder="Rechercher un commerce" value="<?= htmlspecialchars($recherche) ?>"></div>
                <div class="col-md-4">
                    <select name="categorie_id" class="form-select">
                        <option value="">Toutes les catégories</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= (int)$c['id'] ?>" <?= $categorie_id === (int)$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3"><button type="submit" class="btn btn-primary w-100">Rechercher</button></div>
            </form>

            <div class="row g-4 justify-content-center">
                <?php if (empty($commerces)): ?>
                    <div class="text-center"><p>Aucun commerce trouvé.</p></div>
                <?php endif; ?>
                <?php foreach ($commerces as $c): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="course-item bg-light">
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid" src="../assets/img/course-<?= ((int)$c['id'] % 3) + 1 ?>.jpg" alt="">
                            <div class="w-100 d-flex justify-content-center position-absolute bottom-0 start-0 mb-4">
                                <a href="offres.php?commerce_id=<?= (int)$c['id'] ?>" class="btn btn-primary" style="border-radius: 30px;">Voir les offres</a>
                            </div>
                        </div>
                        <div class="text-center p-4 pb-0">
                            <span class="badge bg-secondary mb-2"><?= htmlspecialchars($c['categorie_nom']) ?></span>
                            <h5 class="mb-3"><?= htmlspecialchars($c['nom']) ?></h5>
                            <p><?= htmlspecialchars($c['description'] ?? '') ?></p>
                        </div>
                        <div class="d-flex border-top">
                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-map-marker-alt text-primary me-2"></i><?= htmlspecialchars($c['adresse']) ?></small>
                            <small class="flex-fill text-center py-2"><i class="fa fa-phone text-primary me-2"></i><?= htmlspecialchars($c['telephone']) ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
