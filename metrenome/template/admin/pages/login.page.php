<!doctype html>
<html lang="en" class="centered-page-html form-page-html">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800">

    <link rel="stylesheet" href="./pages/css/reset.css">
    <link rel="stylesheet" href="./pages/css/style.css">
</head>
<body class="centered-page-body">
    <div class="form-page-content">
        <h1 class="page-heading form-page-heading">Admin Panel</h1>

        <form class="form-page-form" action="<?php echo Core::get()->router->getCurrentUrl() ?>" method="POST">
<?php
    if ($tried) {
?>
            <p class="notice notice-error form-page-notice">Password is incorrect</p>
<?php
    }
?>
            <input class="form-page-text-field" id="login-form-password" name="password" type="password" placeholder="Password" data-focusonload>
            <button class="form-page-submit button" name="login-form-submit">Enter</button>
        </form>
    </div>

    <!-- Scripts -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="./pages/js/jquery-1.9.1.min.js"><\/script>')</script>
    <script src="./pages/js/admin.js"></script>
</body>
</html>