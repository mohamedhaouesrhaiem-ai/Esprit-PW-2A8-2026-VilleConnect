<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/AdminC.php';

class ModerationController extends Controller {
    protected $page = 'moderation';
    protected $title = 'Modération | VilleConnect Admin';

    public function index() {
        $adminC = new AdminC();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['user_id'], $_POST['statut'])) {
                $adminC->changerStatutUtilisateur($_POST['user_id'], $_POST['statut']);
            } elseif (isset($_POST['delete_signalement'])) {
                $adminC->supprimerSignalement($_POST['delete_signalement']);
            }
            $this->redirect($this->page);
        }

        $this->render('moderation', [
            'utilisateurs' => $adminC->getUtilisateurs(),
            'signalements' => $adminC->getSignalements(),
        ]);
    }
}
?>
