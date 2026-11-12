<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Controller.php';
class EvenementC extends Controller {
    function getEvenements() {
        $db = config::getConnexion();
        try {
            $sql = "SELECT e.*, l.nom AS lieu_nom FROM Evenement e JOIN Lieu l ON e.lieu_id = l.id";
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) { return []; }
    }
    function addEvenement($ev) {
        $db = config::getConnexion();
        $sql = "INSERT INTO Evenement (titre, description, date_evenement, lieu_id) VALUES (?, ?, ?, ?)";
        $db->prepare($sql)->execute([$ev->getTitre(), $ev->getDescription(), $ev->getDateEvenement(), $ev->getLieuId()]);
    }
    function deleteEvenement($id) {
        $db = config::getConnexion();
        $db->prepare("DELETE FROM Evenement WHERE id = ?")->execute([$id]);
    }
}
?>
