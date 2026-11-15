<?php
class Signalement {
    private $id;
    private $titre;
    private $description;
    private $categorie_id;
    private $statut_id;

    public function __construct($titre, $description, $categorie_id, $statut_id) {
        $this->titre = $titre;
        $this->description = $description;
        $this->categorie_id = $categorie_id;
        $this->statut_id = $statut_id;
    }

    public function getId() { return $this->id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getCategorieId() { return $this->categorie_id; }
    public function getStatutId() { return $this->statut_id; }
}
?>
