<?php

class ClearListController {
    public function run() {
        file_put_contents(ROOT . '/' . Core::get()->config['list_path'], '');
        Core::get()->router->internalRedirect();
    }
}

?>