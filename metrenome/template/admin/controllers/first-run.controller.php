<?php

class FirstRunController {
    public function run() {
        $this->_processForm();

        if (!empty($_SESSION['first_run']['error'])) {
            $vars['error'] = $_SESSION['first_run']['error'];
            unset($_SESSION['first_run']);
        } else {
            $vars['error'] = null;
        }

        Core::get()->renderer->renderPage('first-run', $vars);
    }

    private function _processForm() {
        if (!isset($_POST['first-run-submit'])) {
            return false;
        }

        if (!empty($_POST['password'])) {
            $internal_config = Array(
                'launched' => true,
                'admin_path' => Core::get()->router->definePath(),
                'site_path' => Core::get()->router->definePath('site')
            );

            $config = Core::get()->config;
            $config['password'] = $_POST['password'];

            if (!Core::get()->configurer->saveConfig($internal_config, INTERNAL_CONFIG, true) || !Core::get()->configurer->saveConfig($config, CONFIG, true)) {
                $error = 'unknown';
            }
        } else {
            $error = 'empty_password';
        }

        if (!empty($error)) {
            $_SESSION['first_run']['error'] = $error;
        } else {
            Core::get()->user->logIn();
        }

        Core::get()->router->refresh();
    }
}

?>