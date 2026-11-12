<?php
require_once __DIR__ . '/../config.php';
class InscriptionC {
    function addInscription($inscription) {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO Inscription (evenement_id, nom_participant, email_participant) VALUES (:id, :nom, :email)";
            $query = $db->prepare($sql);
            $query->execute([
                'id' => $inscription->getIdEvenement(),
                'nom' => $inscription->getNomParticipant(),
                'email' => $inscription->getEmailParticipant()
            ]);
            return true;
        } catch (Exception $e) { return false; }
    }
}
?>
