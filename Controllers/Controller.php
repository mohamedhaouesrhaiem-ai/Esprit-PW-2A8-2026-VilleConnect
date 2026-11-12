<?php
require_once __DIR__ . '/../config.php';
class Controller {
    public function render($view, $data = []) {
        extract($data);
        ob_start();
        require_once __DIR__ . '/../Views/back/' . $view . '.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../Views/back/layout.php';
    }
}
?>
