<?php

class Renderer {
    public function renderError($code) {
        $valid_codes = Array(404);

        if (!in_array($code, $valid_codes)) {
            $code = 404;
        }

        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $code);

        return $this->loadPage('error-404');
    }

    public function renderPage($page, $vars = Array()) {
        return $this->loadPage($page, $vars);
    }

    public function renderJson($data, $exit = true) {
        $result = json_encode($data);
        echo $result;

        if ($exit) {
            exit();
        }

        return $result;
    }

    private function loadPage($page, $vars = Array()) {
        $path = PAGES . '/' . $page . '.page.php';

        if (!file_exists($path)) {
            return false;
        }

        extract($vars);
        require_once $path;

        return true;
    }
}

?>