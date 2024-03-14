<?php
/*
Plugin Name: Simply Disable Password Reset
description: Its a very simple plugin to disable the password reset in the wordpress.
Version: 1.0
Author: Boopathi Rajan
Author URI: http://www.boopathirajan.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function boopa_wp_disable_password_reset() { return false; }
add_filter ( 'allow_password_reset', 'boopa_wp_disable_password_reset' );
?>