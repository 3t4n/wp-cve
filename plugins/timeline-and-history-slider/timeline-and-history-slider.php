<?php
/**
 * Plugin Name: Timeline and History Slider
 * Plugin URI:https://www.essentialplugin.com/wordpress-plugin/timeline-history-slider/
 * Text Domain: timeline-and-history-slider
 * Domain Path: /languages/
 * Description: Timeline Plugin for WordPress. Easy to add and display history OR timeline for your WordPress website. Also support WordPress POST post type. Also work with Gutenberg shortcode block. 
 * Author: WP OnlineSupport, Essential Plugin
 * Version: 2.3
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/timeline-history-slider/
 *
 * @package WordPress
 * @author WP OnlineSupport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined('WPOSTAHS_VERSION') ) {
	define( 'WPOSTAHS_VERSION', '2.3' ); // Plugin version
}

if( ! defined( 'WPOSTAHS_DIR' ) ) {
	define( 'WPOSTAHS_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'WPOSTAHS_URL' ) ) {
	define( 'WPOSTAHS_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if( ! defined( 'WPOSTAHS_POST_TYPE' ) ) {
	define( 'WPOSTAHS_POST_TYPE', 'timeline_slider_post' ); // Plugin post type
}

if( ! defined( 'WPOSTAHS_PLUGIN_LINK_UNLOCK' ) ) {
	define( 'WPOSTAHS_PLUGIN_LINK_UNLOCK', 'https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Timeline&utm_campaign=Features-PRO' ); // Plugin link
}

if( ! defined( 'WPOSTAHS_PLUGIN_LINK_UPGRADE' ) ) {
	define( 'WPOSTAHS_PLUGIN_LINK_UPGRADE', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Timeline&utm_campaign=Upgrade-PRO' ); // Plugin Check link
}

if( ! defined( 'WPOSTAHS_SITE_LINK' ) ) {
	define('WPOSTAHS_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wphtsp_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wphtsp_lang_dir = apply_filters( 'wpostahs_languages_directory', $wphtsp_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'timeline-and-history-slider' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'timeline-and-history-slider', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WPOSTAHS_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'timeline-and-history-slider', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'timeline-and-history-slider', false, $wphtsp_lang_dir );
	}
}
add_action('plugins_loaded', 'wpostahs_load_textdomain');

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wpostahs_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wpostahs_uninstall');

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_install() {

	wpostahs_slider_init();
	wpostahs_slider_taxonomies();

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();

	// Deactivate free version
	if( is_plugin_active('timeline-and-history-slider-pro/wp-history-and-timeline-slider.php') ) {
		add_action('update_option_active_plugins', 'wpostahs_deactivate_premium_version');
	}
}

/**
 * Deactivate free plugin
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_deactivate_premium_version() {
	deactivate_plugins('timeline-and-history-slider-pro/wp-history-and-timeline-slider.php', true);
}

/**
 * Plugin Deactivation Function
 * Delete  plugin options
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_uninstall() {
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @package Timeline and History Slider
 * @since 1.0.0
 */
function wpostahs_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = WP_PLUGIN_DIR . '/timeline-and-history-slider-pro/wp-history-and-timeline-slider.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link		= add_query_arg( array( 'message' => 'wpostahs-plugin-notice' ), admin_url( 'plugins.php' ));
	$notice_transient	= get_transient( 'wpostahs_install_notice' );

	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'timeline-and-history-slider'), 'Timeline and History slider').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'timeline-and-history-slider'), '<strong>(<em>Timeline and History slider PRO</em>)</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}
add_action( 'admin_notices', 'wpostahs_admin_notice');

// Function file
require_once( 'includes/wpostahs-functions.php' );

// Script file
require_once( 'includes/class-wpostahs-script.php' );

// Post type file
require_once( 'includes/wpostahs-slider-custom-post.php' );

// Admin Class file
require_once( 'includes/admin/class-wpostahs-admin.php' );

// Shortcode file
require_once( 'includes/shortcode/wpostahs-slider-shortcode.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WPOSTAHS_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Recommended Plugins Starts */
if ( is_admin() ) {
	require_once( WPOSTAHS_DIR . '/wpos-plugins/wpos-recommendation.php' );

	wpos_espbw_init_module( array(
							'prefix'	=> 'wpostahs',
							'menu'		=> 'edit.php?post_type='.WPOSTAHS_POST_TYPE,
							'position'	=> 4,
						));
}
/* Recommended Plugins Ends */

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl43_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'			=> 43,
							'file'			=> plugin_basename( __FILE__ ),
							'name'			=> 'Timeline and History slider',
							'slug'			=> 'timeline-and-history-slider',
							'type'			=> 'plugin',
							'menu'			=> 'edit.php?post_type=timeline_slider_post',
							'text_domain'	=> 'timeline-and-history-slider',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl43_load();
/* Plugin Wpos Analytics Data Ends */