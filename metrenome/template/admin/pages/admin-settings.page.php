<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel Settings / Admin Panel</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800">

    <link rel="stylesheet" href="./pages/css/reset.css">
    <link rel="stylesheet" href="./pages/css/fonts.css">
    <link rel="stylesheet" href="./pages/css/style.css">
</head>
<body>
    <div class="main-content">
        <h1 class="page-heading"><i class="icon-tools"></i>Admin Panel Settings</h1>

        <p class="page-description">These settings are stored in <em>admin.config.php</em> file.</p>

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
            'password' => Array(
                'required' => 'Password should not be empty'
            ),
            'config_path' => Array(
                'required' => 'Config path should not be empty',
                'file_exists' => 'Specified config file does not exist',
                'file_format' => 'The format of the specified file is unknown'
            ),
            'list_path' => Array(
                'required' => 'Subscription list path should not be empty',
                'file_exists' => 'Specified subscription list file does not exist'
            ),
            'other' => Array(
                'save' => 'File can\'t be saved for unknown reason'
            )
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
            <label for="admin-settings-password">Password</label>
            <input type="text" id="admin-settings-password" name="password" value="<?php echo (!empty($settings['password']))? htmlspecialchars($settings['password']) : ''?>">
            <p class="form-tooltip">Your password to access the admin panel</p>

            <label for="admin-settings-config-path">Config file path</label>
            <input type="text" id="admin-settings-config-path" name="config_path" value="<?php echo (!empty($settings['config_path']))? htmlspecialchars($settings['config_path']) : ''?>">
            <p class="form-tooltip">Path to page configuration file. Should be relative to admin directory</p>

            <label for="admin-settings-list-path">Subscription list file path</label>
            <input type="text" id="admin-settings-list-path" name="list_path" value="<?php echo (!empty($settings['list_path']))? htmlspecialchars($settings['list_path']) : ''?>">
            <p class="form-tooltip">Path to subscription list file. Should be relative to admin directory</p>

            <button class="button button-submit" name="admin-settings-submit">Update</button>
        </form>
    </div>

    <div class="sidebar">
        <a class="logo" href="<?php echo Core::get()->router->getAdminUrl()?>">Admin Panel</a>

        <ul class="main-nav">
            <li>
                <a href="<?php echo Core::get()->router->getAdminUrl()?>"><i class="icon-users"></i>Subscriptions</a>
            </li>
            <li class="active">
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