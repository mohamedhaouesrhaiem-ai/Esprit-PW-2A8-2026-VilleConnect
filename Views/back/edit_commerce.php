<?php
require_once __DIR__ . '/../../Controllers/CommerceC.php';
require_once __DIR__ . '/../../Models/Commerce.php';
$controller = new CommerceC();
$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$d = $id ? $controller->getCommerce($id) : false;
if (!$d) { header('Location: admin_commerces.php'); exit; }
$categories = $controller->getCategories();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$errors, $d] = $controller->validate($_POST);
    if (!$errors) {
        $controller->updateCommerce($id, new Commerce($d['nom'], $d['description'], $d['adresse'], $d['telephone'], $d['categorie_id']));
        header('Location: admin_commerces.php');
        exit;
    }
}
$currentPage = 'commerces';
ob_start();
?>
<h3>Modifier le commerce</h3>
<?php require __DIR__ . '/partials/form_commerce.php'; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
