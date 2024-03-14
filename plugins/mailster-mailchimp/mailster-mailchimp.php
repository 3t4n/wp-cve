<?php
/*
Plugin Name: Mailchimp Importer for Mailster
Plugin URI: https://mailster.co/?utm_campaign=wporg&utm_source=Mailchimp&utm_medium=plugin
Description: Import your Lists from Mailchimp into WordPress and use it with the Mailster Newsletter Plugin for WordPress.
Version: 2.0
Author: EverPress
Author URI: https://everpress.co
Text Domain: mailster-mailchimp
License: GPLv2 or later
*/


define( 'MAILSTER_MAILCHIMP_VERSION', '2.0' );
define( 'MAILSTER_MAILCHIMP_FILE', __FILE__ );

require_once dirname( __FILE__ ) . '/classes/mailchimp.class.php';
new MailsterMailchimp();
