<?php
require_once __DIR__ . '/../config.php';


class Controller {

    protected $page = 'statistiques';

    protected $title = 'VilleConnect Admin';

    public function render($view, $data) {
        $title = $this->title;
        $currentPage = $this->page;
        $viewFile = __DIR__ . '/../Views/back/pages/' . $view . '.php';
        require_once __DIR__ . '/../Views/back/layout.php';
    }
}
?>
