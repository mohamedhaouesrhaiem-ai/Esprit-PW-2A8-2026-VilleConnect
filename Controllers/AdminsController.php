<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/AdminC.php';
require_once __DIR__ . '/../Models/Admin.php';

class AdminsController extends Controller {
    protected $page = 'admins';
    protected $title = 'Administrateurs | VilleConnect Admin';

    public function index() {
        $adminC = new AdminC();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['delete_id'])) {
                $adminC->deleteAdmin($_POST['delete_id']);
            } elseif (isset($_POST['user_id'])) {
                $adminC->addAdmin(new Admin($_POST['user_id'], $_POST['niveau'] ?? 'moderateur'));
            }
            $this->redirect($this->page);
        }

        $candidats = array_filter($adminC->getUtilisateurs(), function ($u) { return !$u['admin_id']; });
        $this->render('admins', [
            'admins' => $adminC->getAdmins(),
            'candidats' => $candidats,
            'adminCourant' => JournalActionC::ADMIN_COURANT,
        ]);
    }
}
?>
