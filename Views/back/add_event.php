<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../Controllers/EvenementC.php';
require_once __DIR__ . '/../../Models/Evenement.php';

$db = config::getConnexion();
$lieux = $db->query("SELECT * FROM Lieu")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $desc = $_POST['description'] ?? '';
    $date = $_POST['date'] ?? '';
    $lieu_id = $_POST['lieu_id'] ?? 1;
    
    $event = new Evenement($titre, $desc, $date, $lieu_id);
    $controller = new EvenementC();
    $controller->addEvenement($event);
    header('Location: admin_events.php');
    exit;
}
ob_start();
?>
<h3>Ajouter un événement</h3>
<form method="POST" action="add_event.php" class="p-4 bg-white rounded shadow-sm">
    <div class="mb-3"><label>Titre</label><input type="text" name="titre" class="form-control" required></div>
    <div class="mb-3"><label>Description</label><textarea name="description" class="form-control" required></textarea></div>
    <div class="mb-3"><label>Date</label><input type="datetime-local" name="date" class="form-control" required></div>
    <div class="mb-3">
        <label>Lieu</label>
        <select name="lieu_id" class="form-control">
            <?php foreach($lieux as $l): ?>
                <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Enregistrer</button>
</form>
<?php
$content = ob_get_clean();
require_once __DIR__ . '/layout.php';
?>
