<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/JournalActionC.php';

class JournalController extends Controller {
    protected $page = 'journal';
    protected $title = 'Journal des actions | VilleConnect Admin';

    public function index() {
        $journalC = new JournalActionC();
        $type = $_GET['type'] ?? '';
        $this->render('journal', [
            'actions' => $journalC->getActions($type),
            'types' => $journalC->getTypes(),
            'type' => $type,
        ]);
    }
}
?>
