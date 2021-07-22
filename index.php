<?php
session_start(array(
    'cookie_lifetime' => 21600,
    'read_and_close'  => true,
));
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");
require 'libs/frontController.php';
frontController::main();
?>
