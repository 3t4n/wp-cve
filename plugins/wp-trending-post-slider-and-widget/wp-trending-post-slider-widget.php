<?php
/**
 * Plugin Name: Trending/Popular Post Slider and Widget
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/trending-post-slider-widget/
 * Description: Show Trending/Popular post in page and sidebar with slider/Grid block with different designs. Also work with Gutenberg shortcode block. 
 * Author: WP OnlineSupport, Essential Plugin
 * Version: 1.8
 * Author URI: https://www.essentialplugin.com/
 * Text Domain: wtpsw
 *
 * @package WordPress
 * @author WP OnlineSupport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Basic plugin definitions
 * 
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */

if( ! defined( 'WTPSW_VERSION' ) ) {
	define( 'WTPSW_VERSION', '1.8' ); // Version of plugin
}

if( ! defined( 'WTPSW_DIR' ) ) {
	define( 'WTPSW_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'WTPSW_URL' ) ) {
	define( 'WTPSW_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if( ! defined( 'WTPSW_META_PREFIX' ) ) {
	define( 'WTPSW_META_PREFIX', '_wtpsw_' ); // Plugin meta prefix
}

if( ! defined( 'WTPSW_PLUGIN_LINK_UNLOCK' ) ) {
	define( 'WTPSW_PLUGIN_LINK_UNLOCK', 'https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Trending-Popular&utm_campaign=Features-PRO' ); // Plugin link
}

if( ! defined( 'WTPSW_PLUGIN_LINK_UPGRADE' ) ) {
	define( 'WTPSW_PLUGIN_LINK_UPGRADE', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Trending-Popular&utm_campaign=Upgrade-PRO' ); // Plugin Check link
}

if( ! defined( 'WTPSW_SITE_LINK' ) ) {
	define('WTPSW_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0.0
 */
function wtpsw_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wtpsw_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wtpsw_lang_dir = apply_filters( 'wtpsw_languages_directory', $wtpsw_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'wtpsw' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'wtpsw', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WTPSW_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'wtpsw', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'wtpsw', false, $wtpsw_lang_dir );
	}
}

// Action to load plugin text domain
add_action('plugins_loaded', 'wtpsw_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wtpsw_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wtpsw_uninstall');

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @since 1.0.0
 */
function wtpsw_install(){

	// get settings for the plugin
	$wtpsw_options = get_option( 'wtpsw_options' );

	if( empty( $wtpsw_options ) ) { // Check plugin version option

		// set default settings
		wtpsw_default_settings();

		// Update plugin version to option
		update_option( 'wtpsw_plugin_version', '1.1' );
	}

	// Version 1.1
	$plugin_version = get_option('wtpsw_plugin_version');

	if( version_compare( $plugin_version, '1.0', '=' ) && !isset($wtpsw_options['post_types']) ) {
		$wtpsw_options['post_types'][0] = 'post';
		update_option( 'wtpsw_options', $wtpsw_options );
		update_option( 'wtpsw_plugin_version', '1.1' );
	}

	// Deactivate free version
	if( is_plugin_active( 'featured-and-trending-post-pro/featured-and-trending-post-pro.php' )) {
		add_action( 'update_option_active_plugins', 'wtpsw_deactivate_version' );
	}
}
 
/**
 * Plugin Deactivation Function
 * Delete  plugin options
 * 
 * @since 1.0.0
 */
function wtpsw_uninstall() {
}

/**
 * Deactivate free plugin
 * 
 * @since 1.2.3
 */
function wtpsw_deactivate_version() {

	if( is_plugin_active( 'featured-and-trending-post-pro/featured-and-trending-post-pro.php' ) ) {
		deactivate_plugins( 'featured-and-trending-post-pro/featured-and-trending-post-pro.php', true );
	}
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.2.3
 */
function wtpsw_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = ABSPATH . 'wp-content/plugins/featured-and-trending-post-pro/featured-and-trending-post-pro.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link        = add_query_arg( array('message' => 'wtpsw-plugin-notice'), admin_url('plugins.php') );
	$notice_transient   = get_transient( 'wtpsw_install_notice' );

	// If free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
					<p>
						<strong>'.sprintf( __( 'Thank you for activating %s', 'wtpsw' ), 'Trending/Popular Post Slider and Widget' ).'</strong>.<br/>
						'.sprintf( __( 'It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'wtpsw' ), '<strong>(<em>Featured and Trending Post Pro</em>)</strong>' ).'
					</p>
					<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
				</div>';
	}
}
add_action( 'admin_notices', 'wtpsw_admin_notice' );

// Taking some globals
global $wtpsw_options, $wtpsw_model, $wtpsw_view_by;

// Functions File
require_once( WTPSW_DIR . '/includes/wtpsw-functions.php' );
$wtpsw_options = wtpsw_get_settings();

// Model Class File
require_once( WTPSW_DIR . '/includes/class-wtpsw-model.php' );

// Script Class File
require_once( WTPSW_DIR . '/includes/class-wtpsw-script.php' );

// Admin Class File
require_once( WTPSW_DIR . '/includes/admin/class-wtpsw-admin.php' );

// Shortcode Class File
require_once( WTPSW_DIR . '/includes/shortcode/wtpsw-slider.php' );
require_once( WTPSW_DIR . '/includes/shortcode/wtpsw-gridbox.php' );
require_once( WTPSW_DIR . '/includes/shortcode/wtpsw-carousel.php' );

// Public Class File
require_once( WTPSW_DIR . '/includes/class-wtpsw-public.php' );

// Wigets File
require_once( WTPSW_DIR . '/includes/widgets/class-wtpsw-post-list-widget.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WTPSW_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Recommended Plugins Starts */
if ( is_admin() ) {
	require_once( WTPSW_DIR . '/wpos-plugins/wpos-recommendation.php' );

	wpos_espbw_init_module( array(
							'prefix'	=> 'wtpsw',
							'menu'		=> 'wtpsw-settings',
							'position'	=> 2,
						));
}
/* Recommended Plugins Ends */

/* Plugin Analytics Data */
function wpos_analytics_anl60_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'			=> 60,
							'file'			=> plugin_basename( __FILE__ ),
							'name'			=> 'Trending/Popular Post Slider and Widget',
							'slug'			=> 'wp-trending-post-slider-and-widget',
							'type'			=> 'plugin',
							'menu'			=> 'wtpsw-settings',
							'text_domain'	=> 'wtpsw',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl60_load();
/* Plugin Analytics Data Ends */