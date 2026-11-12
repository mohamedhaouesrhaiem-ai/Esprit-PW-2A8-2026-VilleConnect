<?php
class Inscription {
    private $id;
    private $evenement_id;
    private $nom_participant;
    private $email_participant;
    public function __construct($evenement_id, $nom_participant, $email_participant) {
        $this->evenement_id = $evenement_id;
        $this->nom_participant = $nom_participant;
        $this->email_participant = $email_participant;
    }
    public function getIdEvenement() { return $this->evenement_id; }
    public function getNomParticipant() { return $this->nom_participant; }
    public function getEmailParticipant() { return $this->email_participant; }
}
?>
