<?php
/**
 * Plugin Name: Audio Player with Playlist Ultimate
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/audio-player-playlist/
 * Text Domain: audio-player-with-playlist-ultimate
 * Description: Audio Player with Playlist Ultimate plugin is a jQuery HTML5 Music/Audio Player with Playlist comes with huge possibilities and options. Its comes with 1 styles for grid and 1 for playlist with Single player & Multiple player orientations. It supports shuffle, repeat, volume control, time line progress-bar, Song Title and Artist. Also work with Gutenberg shortcode block. 
 * Domain Path: /languages/
 * Version: 1.3
 * Author:  WP OnlineSupport, Essential Plugin
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/audio-player-playlist/
 * Contributors: WP OnlineSupport
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( !defined( 'APWPULTIMATE_VERSION' ) ) {
	define( 'APWPULTIMATE_VERSION', '1.3' ); // Version of plugin
}
if( ! defined( 'APWPULTIMATE_NAME' ) ) {
	define( 'APWPULTIMATE_NAME', 'Audio Player with Playlist Ultimate' ); // Version of plugin
}
if( !defined( 'APWPULTIMATE_DIR' ) ) {
	define( 'APWPULTIMATE_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( !defined( 'APWPULTIMATE_URL' ) ) {
	define( 'APWPULTIMATE_URL', plugin_dir_url( __FILE__ )); // Plugin url
}
if( !defined( 'APWPULTIMATE_PLUGIN_BASENAME' ) ) {
	define( 'APWPULTIMATE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // plugin base name
}
if(!defined( 'APWPULTIMATE_POST_TYPE' ) ) {
	define('APWPULTIMATE_POST_TYPE', 'apwp-audio-player'); // Plugin post type
}
if( !defined( 'APWPULTIMATE_CAT' ) ) {
	define( 'APWPULTIMATE_CAT', 'apwp-audio-category' ); // Plugin category name
}
if(!defined( 'APWPULTIMATE_META_PREFIX' ) ) {
	define('APWPULTIMATE_META_PREFIX','_apwp_'); // Plugin metabox prefix
}
if(!defined( 'APWPULTIMATE_PLUGIN_UPGRADE' ) ) {
	define('APWPULTIMATE_PLUGIN_UPGRADE','https://www.essentialplugin.com/wordpress-plugin/audio-player-playlist/?utm_source=WP&utm_medium=Audio-Player&utm_campaign=Upgrade-PRO'); // Plugin Check link
}

if(!defined( 'APWPULTIMATE_SITE_LINK' ) ) {
	define('APWPULTIMATE_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
function apwpultimate_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$apwpultimate_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$apwpultimate_lang_dir = apply_filters( 'apwpultimate_languages_directory', $apwpultimate_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'audio-player-with-playlist-ultimate' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'audio-player-with-playlist-ultimate', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( APWPULTIMATE_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'audio-player-with-playlist-ultimate', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'audio-player-with-playlist-ultimate', false, $apwpultimate_lang_dir );
	}
}
add_action('plugins_loaded', 'apwpultimate_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'apwpultimate_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'apwpultimate_uninstall');

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
function apwpultimate_install() {

   // Get settings for the plugin
	$apwpultimate_ultimate_options    = get_option( 'apwpultimate_ultimate_options' );

	if( empty( $apwpultimate_ultimate_options ) ) { // Check plugin version option

		// Set default settings
		apwpultimate_ultimate_set_default_settings();

		// Update plugin version to option
		update_option( 'apwpultimate_ultimate_plugin_version', '1.0' );
	}

	// Version 1.1
	$plugin_version = get_option( 'apwpultimate_ultimate_plugin_version' );

	if( version_compare( $plugin_version, '1.0', '=' ) ) {
		update_option( 'apwpultimate_ultimate_options', $apwpultimate_ultimate_options );
		update_option( 'apwpultimate_ultimate_plugin_version', '1.1' );
	}

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();

	// Deactivate free version
	if( is_plugin_active('audio-player-with-playlist-pro/audio-player-with-playlist-pro.php') ){
		add_action('update_option_active_plugins', 'apwpultimate_ultimate_deactivate_free_version');
	}
}

/**
 * Plugin Functinality (On Deactivation)
 * 
 * Delete  plugin options.
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.0.0
 */
function apwpultimate_uninstall() {
	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();
}

/**
 * Deactivate free plugin
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.3
 */
function apwpultimate_ultimate_deactivate_free_version() {
	deactivate_plugins('audio-player-with-playlist-pro/audio-player-with-playlist-pro.php', true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @package Audio Player with Playlist Ultimate
 * @since 1.3
 */
function apwpultimate_ultimate_admin_notice() {

	global $pagenow;

	$dir                = WP_PLUGIN_DIR . '/audio-player-with-playlist-pro/audio-player-with-playlist-pro.php';
	$notice_link        = add_query_arg( array('message' => 'apwpultimate-ultimate-plugin-notice'), admin_url('plugins.php') );

	// If PRO plugin is active and free plugin exist
	if( $pagenow == 'plugins.php' ) {

		$notice_transient   = get_transient( 'apwpultimate_ultimate_install_notice' );

		if( $notice_transient == false && file_exists( $dir ) && current_user_can( 'install_plugins' ) ) {
			echo '<div class="updated notice" style="position:relative;">
						<p>
							<strong>'.sprintf( __('Thank you for activating %s', 'audio-player-with-playlist-ultimate'), 'Audio Player with Playlist').'</strong>.<br/>
							'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'audio-player-with-playlist-ultimate'), '<strong>(<em>Audio Player with Playlist Ultimate Pro</em>)</strong>' ).'
						</p>
						<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
					</div>';
		}
	}
}

// Action to display notice
add_action( 'admin_notices', 'apwpultimate_ultimate_admin_notice');

// Global variables
global $apwpultimate_ultimate_options;

// Funcions File
require_once( APWPULTIMATE_DIR .'/includes/apwpultimate-functions.php' );
$apwpultimate_ultimate_options = apwpultimate_ultimate_get_settings();

// Post Type File
require_once( APWPULTIMATE_DIR . '/includes/apwpultimate-post-types.php' );

// Script Class File
require_once( APWPULTIMATE_DIR . '/includes/class-apwpultimate-script.php' );

// Admin Class File
require_once( APWPULTIMATE_DIR . '/includes/admin/class-apwpultimate-admin.php' );

// Shortcode file
// Grid Shortcode
require_once( APWPULTIMATE_DIR . '/includes/shortcode/apwpultimate-grid-shortcode.php' );

// Shortcode
require_once( APWPULTIMATE_DIR . '/includes/shortcode/apwpultimate-shortcode.php' );