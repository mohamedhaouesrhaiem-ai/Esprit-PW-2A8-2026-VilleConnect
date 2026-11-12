<?php
require_once __DIR__ . '/../../Controllers/InscriptionC.php';
require_once __DIR__ . '/../../Models/Inscription.php';
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inscription = new Inscription($_POST['event_id'], $_POST['nom'], $_POST['email']);
    $controller = new InscriptionC();
    if ($controller->addInscription($inscription)) { $message = "<div class='alert alert-success'>Inscription réussie !</div>"; } 
    else { $message = "<div class='alert alert-danger'>Erreur.</div>"; }
}
$pageTitle = 'Inscription'; $currentPage = 'events';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="container py-5" style="min-height: 60vh;">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">S'inscrire</h2>
                <?= $message ?>
                <form method="POST" action="inscription.php">
                    <input type="hidden" name="event_id" value="<?= isset($_GET['id']) ? htmlspecialchars($_GET['id']) : '1' ?>">
                    <div class="mb-3"><label class="form-label">Nom complet</label><input type="text" name="nom" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                    <button type="submit" class="btn btn-primary w-100">Valider</button>
                </form>
            </div>
        </div>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
