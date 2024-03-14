<?php
/**
 * Plugin Name:       MailerSend - Official SMTP Integration
 * Description:       Improve your deliverability and avoid the spam box with MailerSend’s SMTP server. Check your analytics to improve your emails for better conversion!
 * Version:           1.0.3
 * Requires at least: 5.7
 * Requires PHP:      7.2.5
 * Author:            MailerSend
 * Author URI:        https://www.mailersend.com
 * Developer:         MailerSend
 * Developer URI:     https://www.mailersend.com
 * License:           GPLv3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       mailersend-official-smtp-integration
 * Domain Path:       -
 */

// Exit if accessed directly
use MailerSend\MailerSend_SMTP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin name
define( 'MAILERSEND_SMTP_NAME', 'MailerSend - Official SMTP Integration' );

// Plugin version
define( 'MAILERSEND_SMTP_VER', '1.0.3' );

// Plugin path
define( 'MAILERSEND_SMTP_DIR', plugin_dir_path( __FILE__ ) );

// Plugin URL
define( 'MAILERSEND_SMTP_URL', plugin_dir_url( __FILE__ ) );

// Plugin basename
define( 'MAILERSEND_SMTP_BASENAME', plugin_basename( __FILE__ ) );

// Minimum PHP version
define( 'MAILERSEND_SMTP_MIN_PHP_VERSION', '7.2.5' );

// MailerSend SMTP HOST
define( 'MAILERSEND_SMTP_HOST', 'smtp.mailersend.net' );

// MailerSend SMTP PORT
define( 'MAILERSEND_SMTP_PORT', 587 );

require_once( MAILERSEND_SMTP_DIR . 'autoload.php' );

/**
 * Returns the main instance of MailerSend_SMTP.
 *
 * @return MailerSend_SMTP
 * @since  1.0.0
 */
function mailersend_instance(): MailerSend_SMTP {

	return new MailerSend\MailerSend_SMTP();
}

mailersend_instance();
