<?php
$pageTitle = 'Événements';
$currentPage = 'events';
require_once __DIR__ . '/../../Controllers/EvenementC.php';
$controller = new EvenementC();
$events = $controller->getEvenements();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once __DIR__ . '/../includes/head.php'; ?>
</head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container-fluid bg-primary py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Nos Ã‰vÃ©nements</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4 justify-content-center">
                <?php if(empty($events)): ?>
                    <div class="text-center"><p>Aucun Ã©vÃ©nement pour le moment. pour le moment.</p></div>
                <?php endif; ?>
                <?php foreach ($events as $event): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="course-item bg-light">
                        <div class="position-relative overflow-hidden">
                            <img class="img-fluid" src="../assets/img/course-1.jpg" alt="">
                            <div class="w-100 d-flex justify-content-center position-absolute bottom-0 start-0 mb-4">
                                <a href="inscription.php?id=<?= htmlspecialchars($event['id']) ?>" class="btn btn-primary" style="border-radius: 30px;">S'inscrire</a>
                            </div>
                        </div>
                        <div class="text-center p-4 pb-0">
                            <h3 class="mb-0"><?= htmlspecialchars($event['date_evenement']) ?></h3>
                            <h5 class="mb-4"><?= htmlspecialchars($event['titre']) ?></h5>
                        </div>
                        <div class="d-flex border-top">
                            <small class="flex-fill text-center py-2"><i class="fa fa-map-marker-alt text-primary me-2"></i><?= htmlspecialchars($event['lieu_nom']) ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
