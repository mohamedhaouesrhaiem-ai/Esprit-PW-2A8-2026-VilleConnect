<?php

if (!isset($currentPage)) {
    $currentPage = 'events';
}

$menu = [
    ['cle' => 'statistiques', 'lien' => 'index.php?page=statistiques', 'texte' => 'Statistiques'],
    ['cle' => 'events',       'lien' => 'admin_events.php',            'texte' => 'Événements'],
    ['cle' => 'signalements', 'lien' => 'admin_signalements.php',      'texte' => 'Signalements'],
    ['cle' => 'moderation',   'lien' => 'index.php?page=moderation',   'texte' => 'Modération'],
    ['cle' => 'admins',       'lien' => 'index.php?page=admins',       'texte' => 'Administrateurs'],
    ['cle' => 'journal',      'lien' => 'index.php?page=journal',      'texte' => 'Journal des actions'],
];
?>
<nav class="bg-dark text-white p-3" style="width: 250px; height: 100vh; position: fixed; top: 0; left: 0;">
    <h4 class="text-white mb-4">VilleConnect Admin</h4>
    <ul class="nav flex-column">
        <?php foreach ($menu as $item) { ?>
        <li class="nav-item mb-2">
            <?php if ($item['cle'] == $currentPage) { ?>
                <a class="nav-link text-white bg-primary" href="<?= $item['lien']; ?>"><?= $item['texte']; ?></a>
            <?php } else { ?>
                <a class="nav-link text-white" href="<?= $item['lien']; ?>"><?= $item['texte']; ?></a>
            <?php } ?>
        </li>
        <?php } ?>
    </ul>
</nav>

