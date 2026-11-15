<?php
require_once __DIR__ . '/../../Controllers/TrajetC.php';
require_once __DIR__ . '/../../Controllers/ReservationC.php';
require_once __DIR__ . '/../includes/mobilite_helpers.php';
$controller = new TrajetC();
$resaC = new ReservationC();

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
$uid = filter_var($_GET['uid'] ?? null, FILTER_VALIDATE_INT);
$trajet = $id ? $controller->getTrajet($id) : false;
// Seul le conducteur du trajet peut le modifier, et seulement s'il est actif.
$autorise = $trajet && $uid && (int)$trajet['conducteur_id'] === $uid && $trajet['statut'] === 'actif';

$errors = [];
if ($autorise) {
    $reservees = $resaC->getPlacesReservees($id);
    $in = [
        'depart' => $trajet['depart'], 'arrivee' => $trajet['arrivee'],
        'date_depart' => date('Y-m-d\TH:i', strtotime($trajet['date_depart'])),
        'places' => $trajet['places'], 'prix' => $trajet['prix'],
    ];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $in = array_merge($in, array_intersect_key($_POST, $in));
        $_POST['conducteur_id'] = $uid;
        [$errors, $d] = $controller->validate($_POST);
        if ($d['places'] !== false && $d['places'] < $reservees) {
            $errors[] = "Impossible de descendre sous $reservees place(s) : elles sont déjà réservées.";
        }
        if (!$errors) {
            $ok = $controller->updateTrajet($id, $uid, new Trajet($uid, $d['depart'], $d['arrivee'], $d['date_depart'], $d['places'], $d['prix']));
            header('Location: mes_trajets.php?uid=' . $uid . '&msg=' . ($ok ? 'modifie' : 'refuse'));
            exit;
        }
    }
}
$pageTitle = 'Modifier un trajet'; $currentPage = 'trajets';
?>
<!DOCTYPE html>
<html lang="fr">
<head><?php require_once __DIR__ . '/../includes/head.php'; ?></head>
<body>
    <?php require_once __DIR__ . '/../includes/spinner.php'; ?>
    <?php require_once __DIR__ . '/../includes/navbar.php'; ?>
    <div class="container py-5" style="min-height: 60vh;">
        <div class="row justify-content-center"><div class="col-md-6">
            <h2 class="text-center mb-4">Modifier un trajet</h2>
            <?php if (!$autorise): ?>
                <div class="alert alert-warning">Trajet introuvable, déjà annulé, ou vous n'en êtes pas le conducteur. <a href="mes_trajets.php">Mes trajets</a></div>
            <?php else: ?>
                <?php if ($errors): ?><div class="alert alert-danger"><?= implode('<br>', array_map('htmlspecialchars', $errors)) ?></div><?php endif; ?>
                <form method="POST" action="modifier_trajet.php?id=<?= (int)$id ?>&uid=<?= (int)$uid ?>">
                    <div class="mb-3"><label class="form-label">Départ</label><?= mobiliteLieuSelect($in['depart'], 'depart') ?></div>
                    <div class="mb-3"><label class="form-label">Arrivée</label><?= mobiliteLieuSelect($in['arrivee'], 'arrivee') ?></div>
                    <div class="mb-3"><label class="form-label">Date et heure de départ</label><input type="datetime-local" name="date_depart" class="form-control" value="<?= htmlspecialchars($in['date_depart']) ?>" required></div>
                    <div class="row">
                        <div class="col-6 mb-3"><label class="form-label">Places (<?= (int)$reservees ?> réservée(s))</label><input type="number" name="places" class="form-control" min="1" max="8" value="<?= htmlspecialchars($in['places']) ?>" required></div>
                        <div class="col-6 mb-3"><label class="form-label">Prix par place (DT)</label><input type="text" name="prix" class="form-control" value="<?= htmlspecialchars($in['prix']) ?>" required></div>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">Enregistrer</button>
                </form>
            <?php endif; ?>
        </div></div>
    </div>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>
    <?php require_once __DIR__ . '/../includes/scripts.php'; ?>
