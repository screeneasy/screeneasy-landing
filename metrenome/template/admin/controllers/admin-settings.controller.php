<?php

class AdminSettingsController {
    public function run() {
        $this->processForm();

        $vars['settings'] = (!empty($_SESSION['admin_settings']['data']))? $_SESSION['admin_settings']['data'] : Core::get()->config;
        $vars['transfer_data'] = (!empty($_SESSION['admin_settings']))? $_SESSION['admin_settings'] : null;
        unset($_SESSION['admin_settings']);

        Core::get()->renderer->renderPage('admin-settings', $vars);
    }

    private function processForm() {
        if (isset($_POST['admin-settings-submit'])) {
            $data = $_POST;

            $rules = Array(
                'password' => 'required',
                'config_path' => 'required',
                'list_path' => 'required'
            );

            $data = Core::get()->validator->filter($data, $rules);

            $is_valid = Core::get()->validator->validate($data, $rules, $errors);

            $config_path = ROOT . '/' . $data['config_path'];
            $list_path = ROOT . '/' . $data['list_path'];

            if (empty($errors['config_path'])) {
                if (!is_file($config_path)) {
                    $errors['config_path'][] = 'file_exists';
                } else if (!Core::get()->configurer->loadConfig($config_path)) {
                    $errors['config_path'][] = 'file_format';
                }
            }

            if (empty($errors['list_path']) && !is_file($list_path)) {
                $errors['list_path'][] = 'file_exists';
            }

            if (!$errors) {
                if (!Core::get()->configurer->saveConfig($data, CONFIG, true)) {
                    $errors['other'][] = 'save';
                }
            }

            if (!$errors) {
                $_SESSION['admin_settings']['status'] = true;
            } else {
                $_SESSION['admin_settings']['status'] = false;
                $_SESSION['admin_settings']['errors'] = $errors;
                $_SESSION['admin_settings']['data'] = $data;
            }

            Core::get()->router->refresh();
        }
    }
}

?>