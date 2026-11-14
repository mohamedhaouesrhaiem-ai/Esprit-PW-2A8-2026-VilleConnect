<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/Admin.php';
require_once __DIR__ . '/JournalActionC.php';

class AdminC {
    private $journal;

    public function __construct() { $this->journal = new JournalActionC(); }

   
    function getAdmins() {
        $db = config::getConnexion();
        try {
            $sql = "SELECT a.*, u.nom, u.email FROM Admin a JOIN Utilisateur u ON a.user_id = u.id ORDER BY a.id";
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) { return []; }
    }

    function addAdmin($admin) {
        $db = config::getConnexion();
        try {
            $db->prepare("INSERT INTO Admin (user_id, niveau) VALUES (?, ?)")
               ->execute([$admin->getUserId(), $admin->getNiveau()]);
            $this->journal->log('ajout_admin', "Utilisateur #" . $admin->getUserId() . " nommé " . $admin->getNiveau());
            return true;
        } catch (Exception $e) { return false; }
    }

    function deleteAdmin($id) {
        $db = config::getConnexion();
        if ((int)$id === JournalActionC::ADMIN_COURANT) { return false; } // on ne se retire pas soi-même
        try {
            $db->prepare("DELETE FROM Admin WHERE id = ?")->execute([$id]);
            $this->journal->log('retrait_admin', "Admin #$id retiré");
            return true;
        } catch (Exception $e) { return false; }
    }


    function getUtilisateurs() {
        $db = config::getConnexion();
        try {
            $sql = "SELECT u.*, a.id AS admin_id FROM Utilisateur u LEFT JOIN Admin a ON a.user_id = u.id ORDER BY u.id";
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) { return []; }
    }

    function changerStatutUtilisateur($id, $statut) {
        if (!in_array($statut, ['actif', 'bloque'], true)) { return; }
        $db = config::getConnexion();
        try {
            $db->prepare("UPDATE Utilisateur SET statut = ? WHERE id = ?")->execute([$statut, $id]);
            $this->journal->log($statut === 'bloque' ? 'blocage' : 'deblocage', "Utilisateur #$id : $statut");
        } catch (Exception $e) { die('Erreur modération'); }
    }

    function supprimerSignalement($id) {
        $db = config::getConnexion();
        try {
            $db->prepare("DELETE FROM Signalement WHERE id = ?")->execute([$id]);
            $this->journal->log('suppression_signalement', "Signalement #$id supprimé (contenu inapproprié)");
        } catch (Exception $e) { die('Erreur suppression'); }
    }

    function getSignalements() {
        $db = config::getConnexion();
        try {
            $sql = "SELECT s.id, s.titre, s.description, st.nom AS statut_nom
                    FROM Signalement s JOIN Statut st ON s.statut_id = st.id ORDER BY s.id DESC";
            return $db->query($sql)->fetchAll();
        } catch (Exception $e) { return []; }
    }

 
    private function compter($sql) {
        try { return (int) config::getConnexion()->query($sql)->fetchColumn(); }
        catch (Exception $e) { return 0; }
    }

    function getStatistiques() {
        $db = config::getConnexion();
        $stats = [
            'utilisateurs'  => $this->compter("SELECT COUNT(*) FROM Utilisateur"),
            'bloques'       => $this->compter("SELECT COUNT(*) FROM Utilisateur WHERE statut = 'bloque'"),
            'evenements'    => $this->compter("SELECT COUNT(*) FROM Evenement"),
            'inscriptions'  => $this->compter("SELECT COUNT(*) FROM Inscription"),
            'signalements'  => $this->compter("SELECT COUNT(*) FROM Signalement"),
            'actions'       => $this->compter("SELECT COUNT(*) FROM JournalAction"),
            'parStatut'     => [],
            'parCategorie'  => [],
        ];
        try {
            $stats['parStatut'] = $db->query(
                "SELECT st.nom, COUNT(s.id) AS total FROM Statut st
                 LEFT JOIN Signalement s ON s.statut_id = st.id GROUP BY st.id, st.nom ORDER BY st.id")->fetchAll();
            $stats['parCategorie'] = $db->query(
                "SELECT c.nom, COUNT(s.id) AS total FROM CategorieSignalement c
                 LEFT JOIN Signalement s ON s.categorie_id = c.id GROUP BY c.id, c.nom ORDER BY c.id")->fetchAll();
        } catch (Exception $e) { }
        return $stats;
    }
}
?>
