<?php
/**
 * Plugin Name: Email Capture & Lead Generation
 * Description: Email Capture & Lead Generation plugin helps to collect emails with first name and last name and get list of subscriber in your WordPress dashboard and download list in CSV formate.
 * Version: 1.0.2
 * Author: wishfulthemes
 * Author URI: https://www.wishfulthemes.com
 * Text Domain: email-capture-lead-generation
 * Domain Path: languages
 *
 * License: GPLv2 or later
 * Domain Path: languages
 *
 * @package Email Capture & Lead Generation
 * @category Core
 * @author WishfulThemes
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

/**
 * Basic plugin definitions
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */
if ( ! defined( 'ECLG_VERSION' ) ) {
	define( 'ECLG_VERSION', '1.0.1' ); // plugin version
}
if ( ! defined( 'ECLG_PLUGIN_DIR' ) ) {
	define( 'ECLG_PLUGIN_DIR', dirname( __FILE__ ) ); // plugin dir
}
if ( ! defined( 'ECLG_ADMIN_DIR' ) ) {
	define( 'ECLG_ADMIN_DIR', ECLG_PLUGIN_DIR . '/includes/admin' ); // plugin admin dir
}
if ( ! defined( 'ECLG_PLUGIN_URL' ) ) {
	define( 'ECLG_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}

/**
 * Initialize all global variables
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */

global $eclg_scripts,$eclg_admin,$eclg_newsletter;

/**
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */
function eclg_load_plugin_textdomain() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'email-capture-lead-generation' );

	load_textdomain( 'email-capture-lead-generation', WP_LANG_DIR . '/email-capture-lead-generation/email-capture-lead-generation' . $locale . '.mo' );
	load_plugin_textdomain( 'email-capture-lead-generation', false, ECLG_PLUGIN_DIR . '/languages' );
}
add_action( 'load_plugins', 'eclg_load_plugin_textdomain' );

/**
 * Activation hook
 *
 * Register plugin activation hook.
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */

register_activation_hook( __FILE__, 'eclg_plugin_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */

register_deactivation_hook( __FILE__, 'eclg_plugin_uninstall' );

/**
 * Plugin Setup Activation hook call back
 *
 * Initial setup of the plugin setting default options
 * and database tables creations.
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */
function eclg_plugin_install() {

	global $wpdb;

	$charset_collate = $wpdb->get_charset_collate();
	$table_name      = $wpdb->prefix . 'eclg_subscribers';

	$sql = "CREATE TABLE $table_name (
		id int(11) NOT NULL AUTO_INCREMENT,
		first_name varchar(255) NULL,
		last_name varchar(255) NULL,
		email varchar(255) NOT NULL,
		comments text NULL,
		user_ip varchar(100) NULL,
		date timestamp NULL,
		PRIMARY KEY (id)
	) $charset_collate;";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );
}

/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 *
 * @package Email Capture & Lead Generation
 * @since 1.0.0
 */
function eclg_plugin_uninstall() {

	global $wpdb;

	/*
	$charset_collate = $wpdb->get_charset_collate();
	$table_name = $wpdb->prefix . 'eclg_subscribers';
	$sql = "DROP TABLE IF EXISTS $table_name";
	$wpdb->query($sql);

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );*/
}


// Includes all scripts class file
require_once ECLG_PLUGIN_DIR . '/includes/class-eclg-scripts.php';

// Includes shortcode class file
require_once ECLG_PLUGIN_DIR . '/includes/class-eclg-shortcodes.php';

// Includes public class file
require_once ECLG_PLUGIN_DIR . '/includes/class-eclg-public.php';

// Includes Admin file
require_once ECLG_ADMIN_DIR . '/class-eclg-admin.php';



