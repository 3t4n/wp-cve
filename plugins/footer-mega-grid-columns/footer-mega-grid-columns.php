<?php
/**
 * Plugin Name: Footer Mega Grid Columns
 * Plugin URL: https://www.essentialplugin.com/wordpress-plugin/footer-mega-grid-columns/
 * Text Domain: footer-mega-grid-columns
 * Description: Footer Mega Grid Columns - For Legacy / Classic / Old Widget Screen: Register a widget area for your theme and allow you to add and display widgets in grid view with multiple columns.
 * Domain Path: /languages/
 * Version: 1.4.1
 * Author: WP OnlineSupport, Essential Plugin
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/footer-mega-grid-columns/
 * Contributors: WP OnlineSupport
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
if( ! defined( 'FMGC_VERSION' ) ) {
	define( 'FMGC_VERSION', '1.4.1' ); // Version of plugin
}

if( ! defined( 'FMGC_NAME' ) ) {
	define( 'FMGC_NAME', 'Footer Mega Grid Columns' ); // name of plugin
}

if( !defined( 'FMGC_DIR' ) ) {
	define( 'FMGC_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'FMGC_URL' ) ) {
	define( 'FMGC_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( ! defined( 'FMGC_PLUGIN_LINK' ) ) {
	define( 'FMGC_PLUGIN_LINK', 'https://www.essentialplugin.com/wordpress-plugin/footer-mega-grid-columns/?utm_source=WP&utm_medium=Footer-Mega-Grid&utm_campaign=Features-PRO' ); // Plugin Link
}
if( ! defined( 'FMGC_PLUGIN_LINK_UPGRADE' ) ) {
	define( 'FMGC_PLUGIN_LINK_UPGRADE', 'https://www.essentialplugin.com/wordpress-plugin/footer-mega-grid-columns/?utm_source=WP&utm_medium=Footer-Mega-Grid&utm_campaign=Upgrade-PRO' ); // Plugin Link
}
if(!defined( 'FMGC_SITE_LINK' ) ) {
	define('FMGC_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Footer Mega Grid Columns
 * @since 1.2
 */
function fmgc_load_textdomain() {
	global $wp_version;

	// Set filter for plugin's languages directory
	$fmgc_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$fmgc_lang_dir = apply_filters( 'fmgc_languages_directory', $fmgc_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'footer-mega-grid-columns' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'footer-mega-grid-columns', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( FMGC_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'footer-mega-grid-columns', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'footer-mega-grid-columns', false, $fmgc_lang_dir );
	}
}

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'fmgc_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 *
 * @since 1.0.0
 */
function fmgc_install() {

	// Deactivate free version
	if( is_plugin_active('footer-mega-grid-columns-pro/footer-mega-grid-columns-pro.php') ) {
		add_action('update_option_active_plugins', 'fmgc_deactivate_pro_version');
	}
}

/**
 * Deactivate free plugin
 * 
 * @since 1.0.0
 */
function fmgc_deactivate_pro_version() {
	deactivate_plugins('footer-mega-grid-columns-pro/footer-mega-grid-columns-pro.php', true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.0.0
 */
function fmgc_admin_notices() {

	global $pagenow;

	$dir				= WP_PLUGIN_DIR . '/footer-mega-grid-columns-pro/footer-mega-grid-columns-pro.php';
	$notice_link 		= add_query_arg( array('message' => 'fmgc-plugin-notice'), admin_url('plugins.php') );
	$notice_transient 	= get_transient( 'fmgc_install_notice' );

	if( $notice_transient == false && $pagenow == 'plugins.php' && file_exists( $dir ) && current_user_can( 'install_plugins' ) ) {

		// If PRO plugin is active and free plugin exist
		if( $notice_transient == false && file_exists( $dir ) && current_user_can( 'install_plugins' ) ) {
			echo '<div class="updated notice" style="position:relative;">
					<p>
						<strong>'.sprintf( __('Thank you for activating %s', 'footer-mega-grid-columns'), 'Footer Mega Grid Columns').'</strong>.<br/>
						'.sprintf( __('It looks like you had Pro version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'footer-mega-grid-columns'), '<strong>(<em>Footer Mega Grid Columns</em>)</strong>' ).'
					</p>
					<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
				</div>';
		}
	}
}
add_action( 'admin_notices', 'fmgc_admin_notices');

// Admin Class File
require_once( FMGC_DIR . '/includes/admin/class-fmgc-admin.php' );

// Script File
require_once( FMGC_DIR . '/includes/class-fgmc-scripts.php' );

// Function File
require_once( FMGC_DIR . '/includes/fmgc-functions.php' );