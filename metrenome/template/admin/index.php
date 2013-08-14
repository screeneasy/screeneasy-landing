<?php

$debug = false;

require_once 'paths.php';

require_once CLASSES . '/core.class.php';
$core = Core::get();
$core->run(false, $debug);

?>