<?php
/**
 * Plugin Name: WP Slick Slider and Image Carousel
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugins/wp-slick-slider-and-image-carousel/
 * Text Domain: wp-slick-slider-and-image-carousel
 * Domain Path: /languages/
 * Description: Easy to add and display wp slick image slider and carousel. Also added Gutenberg block support.
 * Author: WP OnlineSupport, Essential Plugin
 * Version: 3.6
 * Author URI: https://www.essentialplugin.com/wordpress-plugins/wp-slick-slider-and-image-carousel/
 *
 * @package WP Slick Slider and Image Carousel
 * @author Essential Plugin, WP OnlineSupport
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined('WPSISAC_VERSION') ) {
	define( 'WPSISAC_VERSION', '3.6' ); // Plugin version
}
if( ! defined( 'WPSISAC_DIR' ) ) {
	define( 'WPSISAC_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'WPSISAC_URL' ) ) {
	define( 'WPSISAC_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( ! defined( 'WPSISAC_POST_TYPE' ) ) {
	define( 'WPSISAC_POST_TYPE', 'slick_slider' ); // Plugin post type
}
if( ! defined( 'WPSISAC_META_PREFIX' ) ) {
	define( 'WPSISAC_META_PREFIX', '_wpsisac_' ); // Plugin post type
}
if( ! defined( 'WPSISAC_PLUGIN_BUNDLE_LINK' ) ) {
	define('WPSISAC_PLUGIN_BUNDLE_LINK','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Slick-Slider&utm_campaign=Welcome-Screen'); // Plugin link
}
if( ! defined( 'WPSISAC_PLUGIN_LINK_UNLOCK' ) ) {
	define('WPSISAC_PLUGIN_LINK_UNLOCK','https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Slick-Slider&utm_campaign=Features-PRO'); // Plugin link
}
if( ! defined( 'WPSISAC_PLUGIN_LINK_UPGRADE' ) ) {
	define('WPSISAC_PLUGIN_LINK_UPGRADE','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Slick-Slider&utm_campaign=Upgrade-PRO'); // Plugin Check link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0.0
 */
function wpsisac_get_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wpsisac_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wpsisac_lang_dir = apply_filters( 'wpsisac_languages_directory', $wpsisac_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'wp-slick-slider-and-image-carousel' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'wp-slick-slider-and-image-carousel', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WPSISAC_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'wp-slick-slider-and-image-carousel', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'wp-slick-slider-and-image-carousel', false, $wpsisac_lang_dir );
	}
}

/**
 * Do stuff once all the plugin has been loaded
 * 
 * @since 1.0.0
 */
function wpsisac_get_plugins_loaded() {
	wpsisac_get_load_textdomain();
}
add_action('plugins_loaded', 'wpsisac_get_plugins_loaded');

/**
 * Activation Hook
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'free_wpsisac_install_premium_version' );

/**
 * Deactivation Hook
 * Register plugin deactivation hook.
 * 
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wpsisac_uninstall' );

/**
 * Plugin Setup On Activation
 * 
 * Does the initial setup,
 * set default values for the plugin options.
 * 
 * @since 1.0.0
 */
function free_wpsisac_install_premium_version(){

	wpsisac_register_post_type();
	wpsisac_register_taxonomies();

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();

	if( is_plugin_active( 'wp-slick-slider-and-image-carousel-pro/wp-slick-image-slider.php' ) ){
		add_action( 'update_option_active_plugins', 'wpsisac_deactivate_premium_version' );
	}
}

/**
 * Plugin On Deactivation
 * Delete plugin options and etc.
 * 
 * @since 1.0.0
 */
function wpsisac_uninstall() {

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();
}

/**
 * Deactivate free plugin
 * 
 * @since 1.0.0
 */
function wpsisac_deactivate_premium_version() {
   deactivate_plugins( 'wp-slick-slider-and-image-carousel-pro/wp-slick-image-slider.php', true );
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.0.0
 */
function wpsisac_get_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = WP_PLUGIN_DIR . '/wp-slick-slider-and-image-carousel-pro/wp-slick-image-slider.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link        = add_query_arg( array( 'message' => 'wpsisac-plugin-notice' ), admin_url( 'plugins.php' ) );
	$notice_transient   = get_transient( 'wpsisac_install_notice' );

	// If free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
			echo '<div class="updated notice" style="position:relative;">
			<p>
				<strong>'.sprintf( __( 'Thank you for activating %s', 'wp-slick-slider-and-image-carousel' ), 'WP Slick Slider and Image Carousel' ).'</strong>.<br/>
				'.sprintf( __( 'It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'wp-slick-slider-and-image-carousel' ), '<strong>(<em>WP Slick Slider and Image Carousel Pro</em>)</strong>' ).'
			</p>
			<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
		</div>';
	}

}
add_action( 'admin_notices', 'wpsisac_get_admin_notice');

// Function file
require_once( WPSISAC_DIR . '/includes/wpsisac-function.php' );

// Script
require_once( WPSISAC_DIR . '/includes/class-wpsisac-script.php' );

// Post type file
require_once( WPSISAC_DIR . '/includes/wpsisac-post-types.php' );

// Admin File
require_once( WPSISAC_DIR . '/includes/admin/class-wpsisac-admin.php' );

// Shortcode File
require_once( WPSISAC_DIR . '/includes/shortcodes/wpsisac-slider.php' );
require_once( WPSISAC_DIR . '/includes/shortcodes/wpsisac-carousel.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WPSISAC_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl25_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'					=> 25,
							'file'				=> plugin_basename( __FILE__ ),
							'name'				=> 'WP Slick Slider and Image Carousel',
							'slug'				=> 'wp-slick-slider-and-image-carousel',
							'type'				=> 'plugin',
							'menu'				=> 'edit.php?post_type=slick_slider',
							'redirect_page'	=> 'edit.php?post_type=slick_slider&page=wpsisac-solutions-features',
							'text_domain'		=> 'wp-slick-slider-and-image-carousel',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl25_load();
/* Plugin Wpos Analytics Data Ends */