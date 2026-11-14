<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Models/JournalAction.php';

class JournalActionC {
    // Admin connecté : pas encore de module d'authentification, on utilise l'admin n°1
    const ADMIN_COURANT = 1;

    function addAction($action) {
        $db = config::getConnexion();
        try {
            $sql = "INSERT INTO JournalAction (admin_id, type_action, description) VALUES (?, ?, ?)";
            $db->prepare($sql)->execute([$action->getAdminId(), $action->getTypeAction(), $action->getDescription()]);
        } catch (Exception $e) { /* le journal ne doit pas bloquer l'action principale */ }
    }

    function log($type, $description) {
        $this->addAction(new JournalAction(self::ADMIN_COURANT, $type, $description));
    }

    function getActions($type = '') {
        $db = config::getConnexion();
        try {
            $sql = "SELECT j.*, u.nom AS admin_nom
                    FROM JournalAction j
                    JOIN Admin a ON j.admin_id = a.id
                    JOIN Utilisateur u ON a.user_id = u.id";
            $params = [];
            if ($type !== '') { $sql .= " WHERE j.type_action = ?"; $params[] = $type; }
            $sql .= " ORDER BY j.date_action DESC";
            $query = $db->prepare($sql);
            $query->execute($params);
            return $query->fetchAll();
        } catch (Exception $e) { return []; }
    }

    function getTypes() {
        $db = config::getConnexion();
        try {
            return $db->query("SELECT DISTINCT type_action FROM JournalAction ORDER BY type_action")->fetchAll(PDO::FETCH_COLUMN);
        } catch (Exception $e) { return []; }
    }
}
?>
