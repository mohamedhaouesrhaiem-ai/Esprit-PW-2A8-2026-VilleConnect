<?php
/**
 * Point d'entrée du Back Office : index.php?page=statistiques
 */
$page = 'statistiques';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

require_once __DIR__ . '/routes.php';
?>
