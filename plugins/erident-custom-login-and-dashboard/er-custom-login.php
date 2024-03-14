<?php
/**
 * Plugin Name: Erident Custom Login and Dashboard
 * Plugin URI: https://ultimatedashboard.io/
 * Description: Fully customize the WordPress Login Screen.
 * Text Domain: erident-custom-login-and-dashboard
 * Domain Path: /languages
 * Version: 4.3
 * Author: David Vongries
 * Author URI: https://davidvongries.com/
 * License: GPL-3.0

 * @package Custom_Login_Dashboard
 */

// Helper constants.
define( 'CUSTOM_LOGIN_DASHBOARD_PLUGIN_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'CUSTOM_LOGIN_DASHBOARD_PLUGIN_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
define( 'CUSTOM_LOGIN_DASHBOARD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'CUSTOM_LOGIN_DASHBOARD_PLUGIN_VERSION', '4.3' );

load_plugin_textdomain( 'erident-custom-login-and-dashboard', false, basename( dirname( __FILE__ ) ) . '/languages/' );

// Composer
require __DIR__ . '/vendor/autoload.php';

// Helper classes.
require __DIR__ . '/helpers/helpers.php';
require __DIR__ . '/helpers/class-export.php';
require __DIR__ . '/helpers/class-import.php';

// Ajax handler classes.
require __DIR__ . '/ajax/class-save-settings.php';
require __DIR__ . '/ajax/class-reset-settings.php';
require __DIR__ . '/ajax/class-load-default-settings.php';
require __DIR__ . '/ajax/class-migration.php';

// Required classes.
require __DIR__ . '/class-setup.php';
require __DIR__ . '/class-output.php';

/**
 * Function to run on plugin activation.
 */
function cldashboard_activation_script() {

	$settings = get_option( 'plugin_erident_settings', [] );

	if ( empty( $settings ) ) {
		update_option( 'plugin_erident_settings', cldashboard_get_field_default_values() );
	}

}
register_activation_hook( __FILE__, 'cldashboard_activation_script' );

/**
 * Function to run on plugin deactivation.
 */
function cldashboard_deactivation_script() {

	$settings = get_option( 'plugin_erident_settings', [] );

	$clean_deactivation = isset( $settings['dashboard_delete_db'] ) ? $settings['dashboard_delete_db'] : 0;
	$clean_deactivation = 'yes' === strtolower( $clean_deactivation ) ? 1 : $clean_deactivation;
	$clean_deactivation = 'no' === strtolower( $clean_deactivation ) ? 0 : $clean_deactivation;

	if ( $clean_deactivation ) {
		delete_option( 'plugin_erident_settings' );
	}

}
register_deactivation_hook( __FILE__, 'cldashboard_deactivation_script' );

CustomLoginDashboard\Setup::init();
CustomLoginDashboard\Output::init();
