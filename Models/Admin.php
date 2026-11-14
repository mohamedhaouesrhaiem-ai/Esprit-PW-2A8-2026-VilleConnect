<?php
class Admin {
    private $id;
    private $user_id;
    private $niveau;
    private $date_nomination;

    public function __construct($user_id, $niveau = 'moderateur') {
        $this->user_id = $user_id;
        $this->niveau = $niveau;
    }

    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getNiveau() { return $this->niveau; }
    public function getDateNomination() { return $this->date_nomination; }
}
?>
