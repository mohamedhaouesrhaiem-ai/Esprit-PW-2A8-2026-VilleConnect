<?php
class JournalAction {
    private $id;
    private $admin_id;
    private $type_action;
    private $description;
    private $date_action;

    public function __construct($admin_id, $type_action, $description) {
        $this->admin_id = $admin_id;
        $this->type_action = $type_action;
        $this->description = $description;
    }

    public function getId() { return $this->id; }
    public function getAdminId() { return $this->admin_id; }
    public function getTypeAction() { return $this->type_action; }
    public function getDescription() { return $this->description; }
    public function getDateAction() { return $this->date_action; }
}
?>
