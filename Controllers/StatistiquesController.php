<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/AdminC.php';

class StatistiquesController extends Controller {
    protected $page = 'statistiques';
    protected $title = 'Statistiques | VilleConnect Admin';

    public function index() {
        $adminC = new AdminC();
        $this->render('statistiques', ['stats' => $adminC->getStatistiques()]);
    }
}
?>
