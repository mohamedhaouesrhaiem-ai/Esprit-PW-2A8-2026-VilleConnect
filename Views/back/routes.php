<?php
/**
 * Routage du Back Office : chaque page appelle son Controller.
 * Pour ajouter une page : créer sa vue dans pages/, son Controller, puis ajouter un case ici.
 */
switch ($page) {
    case 'moderation':
        require_once __DIR__ . '/../../Controllers/ModerationController.php';
        $controller = new ModerationController();
        break;
    case 'admins':
        require_once __DIR__ . '/../../Controllers/AdminsController.php';
        $controller = new AdminsController();
        break;
    case 'journal':
        require_once __DIR__ . '/../../Controllers/JournalController.php';
        $controller = new JournalController();
        break;
    default:
        require_once __DIR__ . '/../../Controllers/StatistiquesController.php';
        $controller = new StatistiquesController();
}

$controller->index();
?>
