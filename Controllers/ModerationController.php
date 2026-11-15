<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/AdminC.php';

class ModerationController extends Controller {
    protected $page = 'moderation';
    protected $title = 'Modération | VilleConnect Admin';

    public function index() {
        $adminC = new AdminC();

        
        if (isset($_POST['user_id']) && isset($_POST['statut'])) {
            $adminC->changerStatutUtilisateur($_POST['user_id'], $_POST['statut']);
        }
        if (isset($_POST['delete_signalement'])) {
            $adminC->supprimerSignalement($_POST['delete_signalement']);
        }

        $this->render('moderation', [
            'utilisateurs' => $adminC->getUtilisateurs(),
            'adminsIds' => $adminC->getUserIdsAdmins(),
            'signalements' => $adminC->getSignalements()
        ]);
    }
}
?>

