<?php

class TemplateSettingsController {
    public function run() {
        $this->processForm();

        if (!empty($_SESSION['template_settings']['data'])) {
            $vars['settings'] = $_SESSION['template_settings']['data'];
        } else {
            $vars['settings'] = Core::get()->configurer->loadConfig(ROOT . '/' . Core::get()->config['config_path']);
            unset($vars['settings']['email_settings']);
        }

        $vars['transfer_data'] = (!empty($_SESSION['template_settings']))? $_SESSION['template_settings'] : null;
        unset($_SESSION['template_settings']);

        Core::get()->renderer->renderPage('template-settings', $vars);
    }

    private function processForm() {
        if (isset($_POST['template-settings-submit'])) {
            $data = $_POST;
            $expected_data = Array(
                'countdown' => Array(
                    'date' => null,
                    'time' => null
                ),
                'subscription_form_tooltips' => Array(
                    'success' => null,
                    'already_subscribed' => null,
                    'empty_email' => null,
                    'invalid_email' => null,
                    'default_error' => null
                )
            );

            $data = Core::get()->validator->filter($data, $expected_data);

            $countdown_rules = Array(
                'date' => Array(
                    'required',
                    'regexp' => '/^\d{4}-\d{1,2}-\d{1,2}$/'
                ),
                'time' => Array(
                    'required',
                    'regexp' => '/^\d{1,2}:\d{1,2}:\d{1,2}$/'
                )
            );

            $tooltips_rules = Array(
                'success' => 'required',
                'already_subscribed' => 'required',
                'empty_email' => 'required',
                'invalid_email' => 'required',
                'default_error' => 'required'
            );

            $is_countdown_valid = Core::get()->validator->validate($data['countdown'], $countdown_rules, $countdown_errors);
            $is_tooltips_valid = Core::get()->validator->validate($data['subscription_form_tooltips'], $tooltips_rules, $tooltips_errors);

            $countdown_date = explode('-', $data['countdown']['date']);
            $countdown_time = explode(':', $data['countdown']['time']);

            $data['countdown'] = Array(
                'year' => $countdown_date[0],
                'month' => $countdown_date[1],
                'day' => $countdown_date[2],
                'hour' => $countdown_time[0],
                'minute' => $countdown_time[1],
                'second' => $countdown_time[2]
            );

            if ($is_countdown_valid && $is_tooltips_valid) {
                $config_path = ROOT . '/' . Core::get()->config['config_path'];
                $config = Core::get()->configurer->loadConfig($config_path);

                if (!empty($config)) {
                    $config['countdown'] = $data['countdown'];
                    $config['subscription_form_tooltips'] = $data['subscription_form_tooltips'];

                    if (!Core::get()->configurer->saveConfig($config, $config_path)) {
                        $errors['other'][] = 'save';
                    }
                } else {
                    $errors['other'][] = 'load';
                }
            } else {
                if ($countdown_errors) {
                    $errors['countdown'] = $countdown_errors;
                }

                if ($tooltips_errors) {
                    $errors['subscription_form_tooltips'] = $tooltips_errors;
                }
            }

            if (empty($errors)) {
                $_SESSION['template_settings']['status'] = true;
            } else {
                $_SESSION['template_settings']['status'] = false;
                $_SESSION['template_settings']['errors'] = $errors;
                $_SESSION['template_settings']['data'] = $data;
            }

            Core::get()->router->refresh();
        }
    }
}

?>