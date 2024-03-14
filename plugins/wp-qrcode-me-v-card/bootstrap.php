<?php
/**
 * Plugin Name:     QR code MeCard/vCard generator
 * Description:     Share your contact information such as emails, phone number and much more through QR code with WordPress using shortcode, widget or by direct link.
 * Plugin URI:      https://web-marshal.ru/qr-code-mecard-vcard-generator/
 * Author URI:      https://www.linkedin.com/in/stasionok/
 * Author Telegram: https://t.me/stasionok
 * Author:          Stanislav Kuznetsov
 * Version:     1.6.6
 * License:         GPLv2 or later
 * Text Domain:     wp-qrcode-me-v-card
 * Domain Path:     /languages
 *
 * Network:         false
 */

defined( 'ABSPATH' ) || exit;

const WQM_REQUIRED_PHP_VERSION = '7.3'; // because of vendor library require 7.3
const WQM_REQUIRED_WP_VERSION  = '5.0'; // tested just from 5.0

/**
 * Checks if the system requirements are met
 *
 * @return array True if system requirements are met, false if not
 */
function wqm_requirements_met(): array {
	global $wp_version;

	$errors = [];

	$is_ext_loaded = extension_loaded( 'gd' ) && function_exists( 'gd_info' );
	if ( ! $is_ext_loaded ) {
		$errors[] = __( "There is no GD extension loaded in your php. Please load one of them.", 'wp-qrcode-me-v-card' );
	}

	if ( version_compare( PHP_VERSION, WQM_REQUIRED_PHP_VERSION, '<' ) ) {
		$errors[] = sprintf(__( "Your server is running PHP version %s but this plugin requires at least PHP %s. Please run an upgrade.", 'wp-qrcode-me-v-card' ),
			PHP_VERSION,
			WQM_REQUIRED_PHP_VERSION
		);
	}

	if ( version_compare( $wp_version, WQM_REQUIRED_WP_VERSION, '<' ) ) {
		$errors[] = sprintf(__( "Your Wordpress running version is %s but this plugin requires at least version %s. Please run an upgrade.", 'wp-qrcode-me-v-card' ),
			esc_html( $wp_version ),
			WQM_REQUIRED_WP_VERSION
		);
	}

	return $errors;
}

/**
 * Begins execution of the plugin.
 *
 * Plugin run entry point
 *
 */
function wqm_run_qrcode_me_v_card() {
	$plugin = new WQM_Common();
	$plugin->run();
}

/**
 * Check requirements and load main class
 * The main program needs to be in a separate file that only gets loaded if the plugin requirements are met. Otherwise older PHP installations could crash when trying to parse it.
 */
require_once( __DIR__ . '/controller/class-wqm-common.php' );

$errors = wqm_requirements_met();
if ( empty($errors) ) {
	if ( method_exists( WQM_Common::class, 'activate' ) ) {
		register_activation_hook( __FILE__, array( WQM_Common::class, 'activate' ) );
	}

	wqm_run_qrcode_me_v_card();
} else {
	add_action( 'admin_notices', function () use ( $errors ) {
		require_once( dirname( __FILE__ ) . '/views/requirements-error.php' );
	} );
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	deactivate_plugins( '/wp-qrcode-me-v-card/bootstrap.php' );
}

if ( method_exists( WQM_Common::class, 'deactivate' ) ) {
	register_deactivation_hook( __FILE__, array( WQM_Common::class, 'deactivate' ) );
}

if ( method_exists( WQM_Common::class, 'uninstall' ) ) {
	register_uninstall_hook( __FILE__, array( WQM_Common::class, 'uninstall' ) );
}


