<?php
/*
 * Plugin Name: Change Mail Sender
 * Description: Easily change the default WordPress from email name and address.
 * Version: 1.3.0
 * Requires at least: 5.2
 * Requires PHP: 5.6.20
 * Author: WP Mail SMTP
 * Author URI: https://wpmailsmtp.com/
 * Text Domain: cb-mail
 * Domain Path: /assets/languages
 */

// Don't call the file directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'CB_CHANGE_MAIL_SENDER_VERSION', '1.3.0' );

$autoload_path = plugin_dir_path( __FILE__ ) . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

require_once $autoload_path;

/**
 * Global function-holder. Works similar to a singleton's instance().
 *
 * @since 1.3.0
 *
 * @return CBChangeMailSender\Core
 */
function cb_change_mail_sender() {

	static $core;

	if ( ! isset( $core ) ) {
		$core = new \CBChangeMailSender\Core();
	}

	return $core;
}

cb_change_mail_sender();

require_once 'deprecated.php';
