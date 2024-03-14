<?php
/*
Plugin Name: Mailster Mailgun Integration
Plugin URI: https://mailster.co/?utm_campaign=wporg&utm_source=Mailster+Mailgun+Integration&utm_medium=plugin
Description: Uses Mailgun to deliver emails for the Mailster Newsletter Plugin for WordPress.
Version: 1.5
Author: EverPress
Author URI: https://mailster.co
Text Domain: mailster-mailgun
License: GPLv2 or later
*/


define( 'MAILSTER_MAILGUN_VERSION', '1.5' );
define( 'MAILSTER_MAILGUN_REQUIRED_VERSION', '2.4' );
define( 'MAILSTER_MAILGUN_FILE', __FILE__ );

require_once dirname( __FILE__ ) . '/classes/mailgun.class.php';
new MailsterMailgun();
