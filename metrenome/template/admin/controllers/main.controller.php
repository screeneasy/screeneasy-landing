<?php

class MainController {
    public function run() {
        $list_path = ROOT . '/' . Core::get()->config['list_path'];

        if (is_file($list_path)) {
            $vars = Array(
                'subscription_list' => Core::get()->subscription_list->readListFromFile($list_path)
            );
        } else {
            $vars = Array(
                'subscription_list' => Array()
            );
        }

        Core::get()->renderer->renderPage('main', $vars);
    }
}

?>