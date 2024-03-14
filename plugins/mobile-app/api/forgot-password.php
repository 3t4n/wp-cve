<?php

/*
* canvas-api/forgot-password endpoint
* showing custom forgot password page
*/
// Setting up the WordPress enviroment
$root_path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
require_once $root_path . 'wp-load.php';
ob_start();
require $root_path . 'wp-login.php';
ob_end_clean();
// Including the general function for the form
require_once CANVAS_DIR . 'core/form/canvas-form.class.php';
// Including specified functions for registration page
require_once CANVAS_DIR . 'core/form/canvas-forgot-password.class.php';
