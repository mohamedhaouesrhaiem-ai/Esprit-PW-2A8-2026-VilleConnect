<?php
$currentPage = $currentPage ?? 'events';
$menu = [
    'dashboard'    => ['admin_dashboard.php', 'fa-chart-bar', 'Statistiques'],
    'events'       => ['admin_events.php', 'fa-calendar', 'Événements'],
    'signalements' => ['admin_signalements.php', 'fa-exclamation-triangle', 'Signalements'],
    'moderation'   => ['admin_moderation.php', 'fa-user-shield', 'Modération'],
    'admins'       => ['admin_admins.php', 'fa-users-cog', 'Administrateurs'],
    'journal'      => ['admin_journal.php', 'fa-history', 'Journal des actions'],
];
?>
<div class="bg-dark text-white p-3" style="width: 250px; height: 100vh; position: fixed; top: 0; left: 0;">
    <h4 class="text-white mb-4">VilleConnect Admin</h4>
    <ul class="nav flex-column">
        <?php foreach ($menu as $cle => [$lien, $icone, $label]): ?>
        <li class="nav-item mb-2">
            <a class="nav-link text-white <?= $currentPage === $cle ? 'bg-primary' : '' ?>" href="<?= $lien ?>">
                <i class="fa <?= $icone ?> me-2"></i> <?= $label ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
