<?php
/*
Plugin Name: Mailster Google Analytics
Plugin URI: https://mailster.co/?utm_campaign=wporg&utm_source=wordpress.org&utm_medium=plugin&utm_term=Google+Analytics
Description: Integrates Google Analytics with Mailster Newsletter Plugin to track your clicks with the popular Analytics service
Version: 1.5.0
Author: EverPress
Author URI: https://mailster.co
Text Domain: mailster-google-analytics
License: GPLv2 or later
 */

define( 'MAILSTER_GA_VERSION', '1.5.0' );
define( 'MAILSTER_GA_FILE', __FILE__ );

require_once __DIR__ . '/classes/google.analytics.class.php';
new MailsterGoogleAnalytics();
