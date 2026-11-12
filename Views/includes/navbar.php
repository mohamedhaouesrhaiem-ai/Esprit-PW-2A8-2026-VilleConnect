<?php
if (!isset($currentPage)) { $currentPage = ''; }
function navActive($page, $currentPage) { return $page === $currentPage ? 'active' : ''; }
?>
<nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
    <a href="events.php" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-primary"><i class="fa fa-city me-3"></i>VilleConnect</h2>
    </a>
    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="events.php" class="nav-item nav-link <?php echo navActive('events', $currentPage); ?>">Événements</a>
        </div>
    </div>
</nav>
