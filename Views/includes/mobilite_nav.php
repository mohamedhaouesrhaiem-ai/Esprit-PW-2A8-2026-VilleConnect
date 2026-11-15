
<?php
$page = basename($_SERVER['PHP_SELF']);
?>

<div class="d-flex flex-wrap justify-content-between align-items-center mt-4">
<a href="trajets.php" class="text-decoration-none text-dark">
    <h2 class="mb-0">Rechercher un trajet</h2>
</a>
    <div>
        <a href="proposer_trajet.php"
           class="btn <?= $page == 'proposer_trajet.php' ? 'btn-primary' : 'btn-outline-primary' ?> me-2">
            Proposer un trajet
        </a>

        <a href="mes_trajets.php"
           class="btn <?= $page == 'mes_trajets.php' ? 'btn-primary' : 'btn-outline-primary' ?> me-2">
            Mes trajets
        </a>

        <a href="mes_reservations.php"
           class="btn <?= $page == 'mes_reservations.php' ? 'btn-primary' : 'btn-outline-primary' ?>">
            Mes réservations
        </a>
    </div>
</div>
