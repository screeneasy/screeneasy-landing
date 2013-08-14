<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subscriptions / Admin Panel</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800">

    <link rel="stylesheet" href="./pages/css/reset.css">
    <link rel="stylesheet" href="./pages/css/fonts.css">
    <link rel="stylesheet" href="./pages/css/style.css">
</head>
<body>
    <div class="main-content">
        <h1 class="page-heading"><i class="icon-users"></i>Subscriptions</h1>
<?php

$subscribers_number = sizeof($subscription_list);

if ($subscribers_number > 0) {

?>
        <p class="subscription-info"><span class="subscription-info-number"><?php echo $subscribers_number ?></span> subscriber<?php echo ($subscribers_number > 1)? 's' : '' ?> on your list</p>

        <ul class="subscription-list">
<?php

foreach ($subscription_list as $subscriber) {

?>
            <li><?php echo htmlspecialchars($subscriber) ?></li>
<?php

}

?>
        </ul>

        <div class="subscription-actions">
            <a class="button" href="<?php echo Core::get()->router->getPageUrl('email-sending') ?>">Send emails</a>
            <a class="button" id="clear-list-button" href="<?php echo Core::get()->router->getPageUrl('clear-list') ?>">Clear list</a>
        </div>
<?php

} else {

?>
        <p class="subscription-info">You have no subscribers yet</p>
<?php

}

?>
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

    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="./pages/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="./pages/js/admin.js"></script>
</body>
</html>