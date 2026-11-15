<?php
require_once __DIR__ . '/../../Controllers/OffreC.php';
require_once __DIR__ . '/../../Models/Offre.php';
$controller = new OffreC();
$commerces = $controller->getCommerces();
$errors = [];
$d = ['commerce_id' => null, 'titre' => '', 'description' => '', 'reduction' => '', 'date_debut' => date('Y-m-d'), 'date_fin' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$errors, $d] = $controller->validate($_POST);
    if (!$errors) {
        $controller->addOffre(new Offre($d['commerce_id'], $d['titre'], $d['description'], $d['reduction'], $d['date_debut'], $d['date_fin']));
        header('Location: admin_offres.php');
        exit;
    }
}
$currentPage = 'offres';
ob_start();
?>
<h3>Ajouter une offre</h3>
<?php require __DIR__ . '/partials/form_offre.php'; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
