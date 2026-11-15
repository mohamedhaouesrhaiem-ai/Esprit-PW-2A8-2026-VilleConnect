<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/JournalActionC.php';
require_once __DIR__ . '/AdminC.php';

class JournalController extends Controller {
    protected $page = 'journal';
    protected $title = 'Journal des actions | VilleConnect Admin';

    public function index() {
        $journalC = new JournalActionC();
        $adminC = new AdminC();

      
        $type = '';
        if (isset($_GET['type'])) {
            $type = $_GET['type'];
        }
        if (empty($type)) {
            $actions = $journalC->getActions();
        } else {
            $actions = $journalC->getActionsParType($type);
        }

      
        foreach ($actions as $key => $a) {
            $actions[$key]['admin_nom'] = $adminC->getNomAdmin($a['admin_id']);
        }

        $this->render('journal', [
            'actions' => $actions,
            'types' => $journalC->getTypes(),
            'type' => $type
        ]);
    }
}
?>
