<?php
class Trajet {
    private $id;
    private $conducteur_id;
    private $depart;
    private $arrivee;
    private $date_depart;
    private $places;
    private $prix;

    public function __construct($conducteur_id, $depart, $arrivee, $date_depart, $places, $prix) {
        $this->conducteur_id = $conducteur_id;
        $this->depart = $depart;
        $this->arrivee = $arrivee;
        $this->date_depart = $date_depart;
        $this->places = $places;
        $this->prix = $prix;
    }

    public function getId() { return $this->id; }
    public function getConducteurId() { return $this->conducteur_id; }
    public function getDepart() { return $this->depart; }
    public function getArrivee() { return $this->arrivee; }
    public function getDateDepart() { return $this->date_depart; }
    public function getPlaces() { return $this->places; }
    public function getPrix() { return $this->prix; }
}
?>
