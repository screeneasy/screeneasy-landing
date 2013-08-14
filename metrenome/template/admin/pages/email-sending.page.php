<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Sending / Admin Panel</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800">

    <link rel="stylesheet" href="./pages/css/reset.css">
    <link rel="stylesheet" href="./pages/css/fonts.css">
    <link rel="stylesheet" href="./pages/css/style.css">
</head>
<body>
    <div class="main-content">
        <h1 class="page-heading"><i class="icon-email"></i>Email Sending</h1>

        <p class="page-description">Send emails to all subscribers from the list.</p>

<?php

if ($transfer_data) {
    if ($transfer_data['status'] === true) {
        if ($transfer_data['sent']) {
            $emails_text = ($transfer_data['sent'] > 1)? 'emails have' : 'email has';
?>
        <div class="notice notice-success form-notice">
            <h2 class="notice-heading"><?php echo $transfer_data['sent'] . ' ' . $emails_text?> been sent</h2>
        </div>
<?php
        } else {
?>
        <div class="notice notice-error form-notice">
            <h2 class="notice-heading">Error ocurred while sending emails</h2>
            <p class="notice-message">Probably your mail server or PHP is not correctly configured</p>
        </div>
<?php
        }

    } else {
        $form_messages = Array(
            'email' => Array(
                'required' => 'Your email should not be empty',
                'email' => 'Your email is incorrect'
            ),
            'name' => Array(
                'required' => 'Your name should not be empty',
            ),
            'subject' => Array(
                'required' => 'Subject should not be empty',
            ),
            'message' => Array(
                'required' => 'Message should not be empty',
            ),
        );
?>
        <div class="notice notice-error form-notice">
            <h2 class="notice-heading">Some errors have occured while saving the settings</h2>
<?php
        foreach ($transfer_data['errors'] as $type => $errors) {
            foreach ($errors as $error) {
?>
            <p class="notice-message"><?php echo $form_messages[$type][$error]?></p>
<?php
            }
        }
?>
        </div>
<?php
    }
}
?>

        <form class="vertical-form" action="<?php echo Core::get()->router->getCurrentUrl()?>" method="POST">
            <label for="email-sending-email">Your email</label>
            <input type="text" id="email-sending-email" name="email" value="<?php echo (!empty($parameters['email']))? htmlspecialchars($parameters['email']) : ''?>">
            <p class="form-tooltip">Goes to "From" field</p>

            <label for="email-sending-name">Your name</label>
            <input type="text" id="email-sending-name" name="name" value="<?php echo (!empty($parameters['name']))? htmlspecialchars($parameters['name']) : ''?>">
            <p class="form-tooltip">Also goes to "From" field</p>

            <label for="email-sending-notification-subject">Subject</label>
            <input type="text" id="email-sending-notification-subject" name="subject" value="<?php echo (!empty($parameters['subject']))? htmlspecialchars($parameters['subject']) : ''?>">

            <label for="email-sending-message">Message</label>
            <textarea id="email-sending-message" name="message"><?php echo (!empty($parameters['message']))? htmlspecialchars($parameters['message']) : ''?></textarea>

            <button class="button button-submit" name="email-sending-submit">Send</button>
        </form>
    </div>

    <div class="sidebar">
        <a class="logo" href="<?php echo Core::get()->router->getAdminUrl()?>">Admin Panel</a>

        <ul class="main-nav">
            <li class="active">
                <a href="<?php echo Core::get()->router->getAdminUrl()?>"><i class="icon-users"></i>Subscriptions</a>
            </li>
            <li>
                <a href="<?php echo Core::get()->router->getPageUrl('admin-settings')?>"><i class="icon-tools"></i>Admin panel settings</a>
            </li>
            <li>
                <a href="<?php echo Core::get()->router->getPageUrl('email-settings')?>"><i class="icon-email"></i>Email settings</a>
            </li>
            <li>
                <a href="<?php echo Core::get()->router->getPageUrl('template-settings')?>"><i class="icon-pencil"></i>Template settings</a>
            </li>
        </ul>

        <ul class="secondary-nav">
            <li>
                <a href="<?php echo Core::get()->router->getSiteUrl()?>">To the site</a>
            </li>
            <li>
                <a href="<?php echo Core::get()->router->getPageUrl('log-out')?>">Log out</a>
            </li>
        </ul>

        <div class="contacts">
            &copy; Alex Shnayder,<br><a href="mailto:adtomsk@gmail.com">adtomsk@gmail.com</a>
        </div>
    </div>
</body>
</html>