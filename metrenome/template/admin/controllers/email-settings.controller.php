<?php

class EmailSettingsController {
    public function run() {
        $this->processForm();

        if (!empty($_SESSION['email_settings']['data'])) {
            $vars['settings'] = $_SESSION['email_settings']['data'];
        } else {
            $config = Core::get()->configurer->loadConfig(ROOT . '/' . Core::get()->config['config_path']);
            $vars['settings'] = $config['email_settings'];
        }

        $vars['transfer_data'] = (!empty($_SESSION['email_settings']))? $_SESSION['email_settings'] : null;
        unset($_SESSION['email_settings']);

        Core::get()->renderer->renderPage('email-settings', $vars);
    }

    private function processForm() {
        if (isset($_POST['email-settings-submit'])) {
            $data = $_POST;
            $expected_data = Array(
                'email' => null,
                'name' => null,
                'notification_emails' => Array(
                    'subject' => null,
                    'message' => null
                ),
                'subscription_emails' => Array(
                    'enabled' => null,
                    'subject' => null,
                    'message' => null
                )
            );

            $data = Core::get()->validator->filter($data, $expected_data);

            $data['subscription_emails']['enabled'] = !empty($_POST['subscription_emails']['enabled']);

            $general_rules = Array(
                'email' => Array('required', 'email'),
                'name' => 'required'
            );

            $notification_rules = Array(
                'subject' => 'required',
                'message' => 'required'
            );

            $subscription_rules = Array(
                'subject' => 'required',
                'message' => 'required'
            );

            $is_general_valid = Core::get()->validator->validate($data, $general_rules, $general_errors);
            $is_notification_valid = Core::get()->validator->validate($data['notification_emails'], $notification_rules, $notification_errors);
            $is_subscription_valid = Core::get()->validator->validate($data['subscription_emails'], $subscription_rules, $subscription_errors);

            if ($is_general_valid && $is_notification_valid && $is_subscription_valid) {
                $config_path = ROOT . '/' . Core::get()->config['config_path'];

                $config = Core::get()->configurer->loadConfig($config_path);

                if (!empty($config['email_settings'])) {
                    $config['email_settings'] = $data;

                    if (!Core::get()->configurer->saveConfig($config, $config_path)) {
                        $errors['other'][] = 'save';
                    }
                } else {
                    $errors['other'][] = 'load';
                }
            } else {
                if ($general_errors) {
                    $errors = $general_errors;
                }

                if ($subscription_errors) {
                    $errors['subscription_emails'] = $subscription_errors;
                }

                if ($notification_errors) {
                    $errors['notification_emails'] = $notification_errors;
                }
            }

            if (empty($errors)) {
                $_SESSION['email_settings']['status'] = true;
            } else {
                $_SESSION['email_settings']['status'] = false;
                $_SESSION['email_settings']['errors'] = $errors;
                $_SESSION['email_settings']['data'] = $data;
            }

            Core::get()->router->refresh();
        }
    }
}

?>