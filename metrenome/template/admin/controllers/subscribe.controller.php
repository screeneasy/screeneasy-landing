<?php

class SubscribeController {
    public function run() {
        $result['status'] = 'error';

        if (empty($_POST) || empty($_POST['email'])) {
            $result['error'] = 'empty_email';
            Core::get()->renderer->renderJson($result);
        }

        $email = trim($_POST['email']);

        if (!Core::get()->validator->validateValue($email, 'email')) {
            $result['error'] = 'invalid_email';
            Core::get()->renderer->renderJson($result);
        }

        $list_path = ROOT . '/' . Core::get()->config['list_path'];

        if (!is_file($list_path)) {
            $result['error'] = 'list_unreadable';
            Core::get()->renderer->renderJson($result);
        }

        $list = Core::get()->subscription_list;
        $list->readListFromFile($list_path);

        if ($list->isInList($email)) {
            $result['error'] = 'already_subscribed';
            Core::get()->renderer->renderJson($result);
        }

        $config = $config = Core::get()->configurer->loadConfig(ROOT . '/' . Core::get()->config['config_path']);

        if (!empty($config['email_settings']['subscription_emails']['enabled'])) {
            $send_result = Core::get()->mailer->sendEmail($config['email_settings']['name'], $config['email_settings']['email'], $email, $config['email_settings']['subscription_emails']['subject'], $config['email_settings']['subscription_emails']['message']);
        }

        $list->addToList($email);
        $save_result = $list->saveList();

        if ($save_result)  {
            $result['status'] = 'success';
        } else {
            $result['error'] = 'list_unwriteable';
        }

        Core::get()->renderer->renderJson($result);
    }
}

?>