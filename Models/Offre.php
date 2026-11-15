<?php
class Offre {
    private $id;
    private $commerce_id;
    private $titre;
    private $description;
    private $reduction;
    private $date_debut;
    private $date_fin;

    public function __construct($commerce_id, $titre, $description, $reduction, $date_debut, $date_fin) {
        $this->commerce_id = $commerce_id;
        $this->titre = $titre;
        $this->description = $description;
        $this->reduction = $reduction;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
    }

    public function getId() { return $this->id; }
    public function getCommerceId() { return $this->commerce_id; }
    public function getTitre() { return $this->titre; }
    public function getDescription() { return $this->description; }
    public function getReduction() { return $this->reduction; }
    public function getDateDebut() { return $this->date_debut; }
    public function getDateFin() { return $this->date_fin; }
}
?>
