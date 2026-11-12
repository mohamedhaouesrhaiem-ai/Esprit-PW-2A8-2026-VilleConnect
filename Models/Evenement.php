<?php
class Evenement {
    private $id;
    private $titre;
    private $description;
    private $date_evenement;
    private $lieu_id;

    public function __construct($titre, $description, $date_evenement, $lieu_id) {
        $this->titre = $titre;
        $this->description = $description;
        $this->date_evenement = $date_evenement;
        $this->lieu_id = $lieu_id;
    }

    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getDateEvenement() { return $this->date_evenement; }
    public function getLieuId() { return $this->lieu_id; }
}
?>
