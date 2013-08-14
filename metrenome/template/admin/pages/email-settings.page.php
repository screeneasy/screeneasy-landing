<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Settings / Admin Panel</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800">

    <link rel="stylesheet" href="./pages/css/reset.css">
    <link rel="stylesheet" href="./pages/css/fonts.css">
    <link rel="stylesheet" href="./pages/css/style.css">
</head>
<body>
    <div class="main-content">
        <h1 class="page-heading"><i class="icon-email"></i>Email Settings</h1>

        <p class="page-description">These settings are stored in the main configuration file.</p>

<?php

if ($transfer_data) {
    if ($transfer_data['status'] === true) {
?>
        <div class="notice notice-success form-notice">
            <h2 class="notice-heading">Settings have been updated.</h2>
        </div>
<?php
    } else {
        $form_messages = Array(
            'email' => Array(
                'required' => 'Your email should not be empty',
                'email' => 'Your email is incorrect'
            ),
            'name' => Array(
                'required' => 'Your name should not be empty'
            ),
            'notification_emails' => Array(
                'subject' => Array(
                    'required' => 'Notification email subject should not be empty'
                ),
                'message' => Array(
                    'required' => 'Notification email message should not be empty'
                )
            ),
            'subscription_emails' => Array(
                'subject' => Array(
                    'required' => 'Subscription email subject should not be empty'
                ),
                'message' => Array(
                    'required' => 'Subscription email message should not be empty'
                )
            ),
            'other' => Array(
                'save' => 'File can\'t be saved for unknown reason'
            )
        );
?>
        <div class="notice notice-error form-notice">
            <h2 class="notice-heading">Some errors have occured while saving the settings</h2>
<?php
        foreach ($transfer_data['errors'] as $key_1 => $value_1) {
            foreach ($value_1 as $key_2 => $value_2) {
                if (is_string($value_2)) {
?>
            <p class="notice-message"><?php echo $form_messages[$key_1][$value_2]?></p>
<?php
                } else if (is_array($value_2)) {
                    foreach ($value_2 as $value_3) {
?>
            <p class="notice-message"><?php echo $form_messages[$key_1][$key_2][$value_3]?></p>
<?php
                    }
                }
            }
        }
?>
        </div>
<?php
    }
}
?>

        <form class="vertical-form" action="<?php echo Core::get()->router->getCurrentUrl()?>" method="POST">
            <label for="email-settings-email">Your email</label>
            <input type="text" id="email-settings-email" name="email" value="<?php echo (!empty($settings['email']))? htmlspecialchars($settings['email']) : ''?>">
            <p class="form-tooltip">Goes to "From" field</p>

            <label for="email-settings-name">Your name</label>
            <input type="text" id="email-settings-name" name="name" value="<?php echo (!empty($settings['name']))? htmlspecialchars($settings['name']) : ''?>">
            <p class="form-tooltip">Also goes to "From" field</p>

            <h2 class="form-section-heading">Notification emails</h2>

            <p class="form-section-description">These are settings for emails which you can send from the "Subscriptions" page to all your subscribers. You will be able to adjust them right before the sending.</p>

            <label for="email-settings-notification-subject">Subject</label>
            <input type="text" id="email-settings-notification-subject" name="notification_emails[subject]" value="<?php echo (!empty($settings['notification_emails']['subject']))? htmlspecialchars($settings['notification_emails']['subject']) : ''?>">

            <label for="email-settings-message">Message</label>
            <textarea id="email-settings-message" name="notification_emails[message]"><?php echo (!empty($settings['notification_emails']['message']))? htmlspecialchars($settings['notification_emails']['message']) : ''?></textarea>


            <h2 class="form-section-heading">Subscription emails</h2>

            <label><input type="checkbox" name="subscription_emails[enabled]" value="true" <?php echo (!empty($settings['subscription_emails']['enabled']))? 'checked' : ''?>>Enable sending an email to a subscribing user</label>

            <label for="email-settings-subscription-subject">Subject</label>
            <input type="text" id="email-settings-subscription-subject" name="subscription_emails[subject]" value="<?php echo (!empty($settings['subscription_emails']['subject']))? htmlspecialchars($settings['subscription_emails']['subject']) : ''?>">

            <label for="email-settings-subscription-message">Message</label>
            <textarea id="email-settings-subscription-message" name="subscription_emails[message]"><?php echo (!empty($settings['subscription_emails']['message']))? htmlspecialchars($settings['subscription_emails']['message']) : ''?></textarea>

            <button class="button button-submit" name="email-settings-submit">Update</button>
        </form>
    </div>

    <div class="sidebar">
        <a class="logo" href="<?php echo Core::get()->router->getAdminUrl()?>">Admin Panel</a>

        <ul class="main-nav">
            <li>
                <a href="<?php echo Core::get()->router->getAdminUrl()?>"><i class="icon-users"></i>Subscriptions</a>
            </li>
            <li>
                <a href="<?php echo Core::get()->router->getPageUrl('admin-settings')?>"><i class="icon-tools"></i>Admin panel settings</a>
            </li>
            <li class="active">
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