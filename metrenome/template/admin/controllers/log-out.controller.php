<?php

class LogOutController {
    public function run() {
        Core::get()->user->logOut();
        Core::get()->router->internalRedirect('login');
    }
}

?>