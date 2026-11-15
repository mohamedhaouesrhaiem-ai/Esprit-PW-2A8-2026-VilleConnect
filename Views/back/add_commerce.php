<?php
require_once __DIR__ . '/../../Controllers/CommerceC.php';
require_once __DIR__ . '/../../Models/Commerce.php';
$controller = new CommerceC();
$categories = $controller->getCategories();
$errors = [];
$d = ['nom' => '', 'description' => '', 'adresse' => '', 'telephone' => '', 'categorie_id' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$errors, $d] = $controller->validate($_POST);
    if (!$errors) {
        $controller->addCommerce(new Commerce($d['nom'], $d['description'], $d['adresse'], $d['telephone'], $d['categorie_id']));
        header('Location: admin_commerces.php');
        exit;
    }
}
$currentPage = 'commerces';
ob_start();
?>
<h3>Ajouter un commerce</h3>
<?php require __DIR__ . '/partials/form_commerce.php'; ?>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
