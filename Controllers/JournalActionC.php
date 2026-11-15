<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/JournalAction.php';


define('ADMIN_COURANT', 1);

class JournalActionC {

    function addAction($action) {
        $sql = "INSERT INTO JournalAction (admin_id, type_action, description)
                VALUES (:admin_id, :type_action, :description)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'admin_id' => $action->getAdminId(),
                'type_action' => $action->getTypeAction(),
                'description' => $action->getDescription()
            ]);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    function getActions() {
        $sql = "SELECT * FROM JournalAction";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            // les actions les plus récentes en premier
            return array_reverse($query->fetchAll());
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

    function getActionsParType($type) {
        $sql = "SELECT * FROM JournalAction WHERE type_action = :type";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['type' => $type]);
            return array_reverse($query->fetchAll());
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
            return [];
        }
    }

  
    function getTypes() {
        $types = [];
        foreach ($this->getActions() as $action) {
            if (!in_array($action['type_action'], $types)) {
                $types[] = $action['type_action'];
            }
        }
        return $types;
    }
}
?>
