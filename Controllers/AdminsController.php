<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/AdminC.php';
require_once __DIR__ . '/../Models/Admin.php';

class AdminsController extends Controller {
    protected $page = 'admins';
    protected $title = 'Administrateurs | VilleConnect Admin';

    public function index() {
        $adminC = new AdminC();

   
        if (isset($_POST['delete_id'])) {
            $adminC->deleteAdmin($_POST['delete_id']);
        }
       
        if (isset($_POST['user_id']) && isset($_POST['niveau'])) {
            $admin = new Admin($_POST['user_id'], $_POST['niveau']);
            $adminC->addAdmin($admin);
        }

   
        $adminsIds = $adminC->getUserIdsAdmins();
        $candidats = [];
        foreach ($adminC->getUtilisateurs() as $u) {
            if (!in_array($u['id'], $adminsIds)) {
                $candidats[] = $u;
            }
        }

        $this->render('admins', [
            'admins' => $adminC->getAdmins(),
            'candidats' => $candidats
        ]);
    }
}
?>
