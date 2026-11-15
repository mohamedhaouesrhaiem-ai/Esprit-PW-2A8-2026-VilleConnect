<?php
require_once __DIR__ . '/../../Controllers/OffreC.php';
require_once __DIR__ . '/../../Models/Offre.php';
$controller = new OffreC();
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$d = $id ? $controller->getOffre($id) : false;
if (!$d) { header('Location: admin_offres.php'); exit; }
$commerces = $controller->getCommerces();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$errors, $d] = $controller->validate($_POST);
    if (!$errors) {
        $controller->updateOffre($id, new Offre($d['commerce_id'], $d['titre'], $d['description'], $d['reduction'], $d['date_debut'], $d['date_fin']));
        header('Location: admin_offres.php');
        exit;
    }
}
$currentPage = 'offres';
ob_start();
?>
<h3>Modifier l'offre</h3>
<?php require __DIR__ . '/partials/form_offre.php'; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
