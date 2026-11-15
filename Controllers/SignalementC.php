<?php
require_once __DIR__ . '/../config.php';

class SignalementC {
    
    function getSignalements() {
        $db = config::getConnexion();
        try {
            $sql = "SELECT s.*, c.nom AS categorie_nom, st.nom AS statut_nom 
                    FROM Signalement s 
                    JOIN CategorieSignalement c ON s.categorie_id = c.id 
                    JOIN Statut st ON s.statut_id = st.id";
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) { return []; }
    }

    function getCategories() {
        return config::getConnexion()->query("SELECT * FROM CategorieSignalement ORDER BY id")->fetchAll();
    }

    function getStatuts() {
        return config::getConnexion()->query("SELECT * FROM Statut ORDER BY id")->fetchAll();
    }

    function addSignalement($signalement) {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO Signalement (titre, description, categorie_id, statut_id) VALUES (?, ?, ?, ?)";
            $db->prepare($sql)->execute([
                $signalement->getTitre(), 
                $signalement->getDescription(), 
                $signalement->getCategorieId(), 
                $signalement->getStatutId()
            ]);
        } catch (Exception $e) { die('Erreur ajout'); }
    }

    function updateStatut($id, $statut_id) {
        $db = config::getConnexion();
        try {
            $sql = "UPDATE Signalement SET statut_id = ? WHERE id = ?";
            $db->prepare($sql)->execute([$statut_id, $id]);
        } catch (Exception $e) { die('Erreur modification'); }
    }
}
?>
