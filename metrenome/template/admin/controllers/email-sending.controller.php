<?php

class EmailSendingController {
    public function run() {
        $this->processForm();

        $list_path = ROOT . '/' . Core::get()->config['list_path'];
        $list = Core::get()->subscription_list->readListFromFile($list_path);

        if (empty($list)) {
            Core::get()->router->internalRedirect();
        }

        if (!empty($_SESSION['email_sending']['data'])) {
            $vars['parameters'] = $_SESSION['email_sending']['data'];
        } else {
            $settings = Core::get()->configurer->loadConfig(ROOT . '/' . Core::get()->config['config_path']);

            $vars['parameters'] = Array(
                'email' => $settings['email_settings']['email'],
                'name' => $settings['email_settings']['name'],
                'subject' => $settings['email_settings']['notification_emails']['subject'],
                'message' => $settings['email_settings']['notification_emails']['message']
            );
        }

        $vars['transfer_data'] = (!empty($_SESSION['email_sending']))? $_SESSION['email_sending'] : null;

        unset($_SESSION['email_sending']);

        Core::get()->renderer->renderPage('email-sending', $vars);
    }

    private function processForm() {
        if (isset($_POST['email-sending-submit'])) {
            $data = $_POST;
            $expected_data = Array(
                'email' => null,
                'name' => null,
                'subject' => null,
                'message' => null
            );

            $data = Core::get()->validator->filter($data, $expected_data);

            $rules = Array(
                'email' => Array('required', 'email'),
                'name' => 'required',
                'subject' => 'required',
                'message' => 'required'
            );

            $is_valid = Core::get()->validator->validate($data, $rules, $errors);
            $sent = 0;

            if ($is_valid) {
                $list_path = ROOT . '/' . Core::get()->config['list_path'];
                $list = Core::get()->subscription_list->readListFromFile($list_path);

                if (!empty($list)) {
                    foreach ($list as $email) {
                        $send_result = Core::get()->mailer->sendEmail($data['name'], $data['email'], $email, $data['subject'], $data['message']);

                        if ($send_result) {
                            $sent++;
                        }
                    }
                } else {
                    $errors['other'] = 'empty_list';
                }
            }

            $_SESSION['email_sending']['status'] = empty($errors);
            $_SESSION['email_sending']['sent'] = $sent;
            $_SESSION['email_sending']['data'] = $data;
            $_SESSION['email_sending']['errors'] = $errors;

            Core::get()->router->refresh();
        }
    }
}

?>