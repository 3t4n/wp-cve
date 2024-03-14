<?php
/*
Plugin Name: Mailster Gmail Integration
Plugin URI: https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=plugin&utm_term=Gmail
Description: Uses Gmail to deliver emails for the Mailster Newsletter Plugin for WordPress.
Version: 1.3.1
Author: EverPress
Author URI: https://mailster.co
Text Domain: mailster-gmail
License: GPLv2 or later
*/


define( 'MAILSTER_GMAIL_VERSION', '1.3.1' );
define( 'MAILSTER_GMAIL_REQUIRED_VERSION', '2.4.11' );
define( 'MAILSTER_GMAIL_FILE', __FILE__ );


require_once dirname( __FILE__ ) . '/vendor/autoload.php';
require_once dirname( __FILE__ ) . '/classes/gmail.class.php';
new MailsterGmail();
