<?php
class Commerce {
    private $id;
    private $nom;
    private $description;
    private $adresse;
    private $telephone;
    private $categorie_id;

    public function __construct($nom, $description, $adresse, $telephone, $categorie_id) {
        $this->nom = $nom;
        $this->description = $description;
        $this->adresse = $adresse;
        $this->telephone = $telephone;
        $this->categorie_id = $categorie_id;
    }

    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
    public function getDescription() { return $this->description; }
    public function getAdresse() { return $this->adresse; }
    public function getTelephone() { return $this->telephone; }
    public function getCategorieId() { return $this->categorie_id; }
}
?>
