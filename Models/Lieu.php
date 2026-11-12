<?php
class Lieu {
    private $id;
    private $nom;
    private $adresse;
    private $ville;

    public function __construct($nom, $adresse, $ville) {
        $this->nom = $nom;
        $this->adresse = $adresse;
        $this->ville = $ville;
    }
    public function getId() { return $this->id; }
    public function getNom() { return $this->nom; }
}
?>
