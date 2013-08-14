<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Template Settings</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800">

    <link rel="stylesheet" href="./pages/css/reset.css">
    <link rel="stylesheet" href="./pages/css/fonts.css">
    <link rel="stylesheet" href="./pages/css/style.css">
</head>
<body>
    <div class="main-content">
        <h1 class="page-heading"><i class="icon-pencil"></i>Template Settings</h1>

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
            'countdown' => Array(
                'date' => Array(
                    'required' => 'Countdown date should not be empty',
                    'regexp' => 'Countdown date is incorrect'
                ),
                'time' => Array(
                    'required' => 'Countdown time should not be empty',
                    'regexp' => 'Countdown time is incorrect'
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
        if (!empty($transfer_data['errors']['subscription_form_tooltips'])) {
            unset($transfer_data['errors']['subscription_form_tooltips']);
?>
            <p class="notice-message">All subscription tooltip messages should not be empty</p>
<?php
        }

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
            <h2 class="form-section-heading">Countdown</h2>

<?php
    $countdown['date'] = date('Y-m-d', mktime(0, 0, 0, $settings['countdown']['month'], $settings['countdown']['day'], $settings['countdown']['year']));
    $countdown['time'] = date('H:i:s', mktime($settings['countdown']['hour'], $settings['countdown']['minute'], $settings['countdown']['second']));
?>

            <label for="template-settings-countdown-date">Date</label>
            <input type="date" min="<?php echo date('Y-m-d')?>" id="template-settings-countdown-date" name="countdown[date]" value="<?php echo $countdown['date']?>" data-hideifsupported="template-settings-countdown-date-tooltip" value="">
            <p class="form-tooltip" id="template-settings-countdown-date-tooltip">In the following format: YYYY.MM.DD</p>

            <label for="template-settings-countdown-time">Time</label>
            <input type="time" id="template-settings-countdown-time" name="countdown[time]" value="<?php echo $countdown['time']?>" step="1" data-hideifsupported="template-settings-countdown-time-tooltip" value="<?php echo (!empty($settings['countdown']['time']))? htmlspecialchars($settings['countdown']['time']) : ''?>">
            <p class="form-tooltip" id="template-settings-countdown-time-tooltip">In the following format: HH:MM:SS</p>

            <h2 class="form-section-heading">Subscription form tooltips</h2>

            <label for="template-settings-tooltips-success">On successful subscription</label>
            <input type="text" id="template-settings-tooltips-success" name="subscription_form_tooltips[success]" value="<?php echo (!empty($settings['subscription_form_tooltips']['success']))? htmlspecialchars($settings['subscription_form_tooltips']['success']) : ''?>">

            <label for="template-settings-tooltips-already-subscribed">When email is already subscribed</label>
            <input type="text" id="template-settings-tooltips-already-subscribed" name="subscription_form_tooltips[already_subscribed]" value="<?php echo (!empty($settings['subscription_form_tooltips']['already_subscribed']))? htmlspecialchars($settings['subscription_form_tooltips']['already_subscribed']) : ''?>">

            <label for="template-settings-tooltips-empty-email">When user submits empty email field</label>
            <input type="text" id="template-settings-tooltips-empty-email" name="subscription_form_tooltips[empty_email]" value="<?php echo (!empty($settings['subscription_form_tooltips']['empty_email']))? htmlspecialchars($settings['subscription_form_tooltips']['empty_email']) : ''?>">

            <label for="template-settings-tooltips-invalid-email">When submitted email's format is invalid</label>
            <input type="text" id="template-settings-tooltips-invalid-email" name="subscription_form_tooltips[invalid_email]" value="<?php echo (!empty($settings['subscription_form_tooltips']['invalid_email']))? htmlspecialchars($settings['subscription_form_tooltips']['invalid_email']) : ''?>">

            <label for="template-settings-tooltips-default-error">When there is some unknown error with subscription</label>
            <input type="text" id="template-settings-tooltips-default-error" name="subscription_form_tooltips[default_error]" value="<?php echo (!empty($settings['subscription_form_tooltips']['default_error']))? htmlspecialchars($settings['subscription_form_tooltips']['default_error']) : ''?>">

            <button class="button button-submit" name="template-settings-submit">Update</button>
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
            <li>
                <a href="<?php echo Core::get()->router->getPageUrl('email-settings')?>"><i class="icon-email"></i>Email settings</a>
            </li>
            <li class="active">
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

    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="./pages/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="./pages/js/admin.js"></script>
</body>
</html>