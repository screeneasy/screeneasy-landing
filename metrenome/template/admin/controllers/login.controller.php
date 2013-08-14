<?php

class LoginController {
    public function run() {
        $this->_processForm();

        if (!empty($_SESSION['login_form']['tried'])) {
            $vars['tried'] = true;
            unset($_SESSION['login_form']);
        } else {
            $vars['tried'] = false;
        }

        Core::get()->renderer->renderPage('login', $vars);
    }

    private function _processForm() {
        if (!isset($_POST['login-form-submit'])) {
            return false;
        }

        if (!empty($_POST['password'])) {
            $password = $_POST['password'];

            if (Core::get()->user->checkPassword($password)) {
                Core::get()->user->logIn();

                Core::get()->router->internalRedirect();
            }
        }

        $_SESSION['login_form']['tried'] = true;

        Core::get()->router->refresh();
    }
}

?>