<?php
/**
 * Plugin Name: WP Testimonials with Rotator Widget
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/wp-testimonial-with-widget/
 * Text Domain: wp-testimonial-with-widget
 * Domain Path: /languages/
 * Description: Easy to add and display client's testimonial on your website with rotator widget. Also work with Gutenberg shortcode block.
 * Author: WP OnlineSupport, Essential Plugin
 * Version: 3.5
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/wp-testimonial-with-widget/
 *
 * @package WP Testimonials with rotator widget
 * @author WP OnlineSupport
 */

if( ! defined( 'WTWP_VERSION' ) ) {
	define( 'WTWP_VERSION', '3.5' ); // Version of plugin
}
// if( ! defined( 'WTWP_NAME' ) ) {
// 	define( 'WTWP_NAME', 'Testimonials with rotator widget' ); // Version of plugin
// }
if( ! defined( 'WTWP_DIR' ) ) {
	define( 'WTWP_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'WTWP_URL' ) ) {
	define( 'WTWP_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( ! defined( 'WTWP_POST_TYPE' ) ) {
	define( 'WTWP_POST_TYPE', 'testimonial' ); // Plugin post type
}
if( ! defined( 'WTWP_CAT' ) ) {
	define( 'WTWP_CAT', 'testimonial-category' ); // Plugin category name
}
if( ! defined( 'WTWP_PLUGIN_BUNDLE_LINK' ) ) {
	define( 'WTWP_PLUGIN_BUNDLE_LINK', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Testimonials&utm_campaign=Welcome-Screen' ); // Plugin link
}
if( ! defined( 'WTWP_PLUGIN_LINK_UNLOCK' ) ) {
	define( 'WTWP_PLUGIN_LINK_UNLOCK', 'https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Testimonials&utm_campaign=Features-PRO' ); // Plugin link
}
if( ! defined( 'WTWP_PLUGIN_LINK_UPGRADE' ) ) {
	define( 'WTWP_PLUGIN_LINK_UPGRADE', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Testimonials&utm_campaign=Upgrade-PRO' ); // Plugin Check link
}

/**
 * Do stuff once all the plugin has been loaded
 * 
 * @since 1.0
 */
function wp_testimonials_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wtwp_pro_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wtwp_pro_lang_dir = apply_filters( 'wtwp_languages_directory', $wtwp_pro_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'wp-testimonial-with-widget' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'wp-testimonial-with-widget', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WTWP_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'wp-testimonial-with-widget', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'wp-testimonial-with-widget', false, $wtwp_pro_lang_dir );
	}
}
add_action( 'plugins_loaded', 'wp_testimonials_load_textdomain' );

/**
 * Activation Hook
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wtwp_install' );

/**
 * Plugin Setup (On Activation)
 * 
 * Does the initial setup,
 * stest default values for the plugin options.
 * 
 * @since 1.0.0
 */
function wtwp_install() {

	wtwp_register_post_types();
	wtwp_register_taxonomies();

	flush_rewrite_rules();

	// To deactivate the free version of plugin
	if( is_plugin_active( 'wp-testimonial-with-widget-pro/wp-testimonials.php' ) ){
		add_action( 'update_option_active_plugins', 'wtwp_deactivate_version' );
	}
}

/**
 * Function to deactivate the free version plugin
 * 
 * @since 1.0.0
 */
function wtwp_deactivate_version(){
	deactivate_plugins( 'wp-testimonial-with-widget-pro/wp-testimonials.php', true );
}

// Action to add admin notice
add_action( 'admin_notices', 'wtwp_admin_notice');

/**
 * Admin notice
 * 
 * @since 1.0.0
 */
function wtwp_admin_notice() {
	
	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	$dir = WP_PLUGIN_DIR . '/wp-testimonial-with-widget-pro/wp-testimonials.php';
	
	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link        = add_query_arg( array('message' => 'wtwp-plugin-notice'), admin_url('plugins.php') );
	$notice_transient   = get_transient( 'wtwp_install_notice' );

	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
		   echo '<div class="updated notice" style="position:relative;">
					<p>
						<strong>'.sprintf( __( 'Thank you for activating %s', 'wp-testimonial-with-widget' ), 'WP Testimonials with rotator widget' ).'</strong>.<br/>
						'.sprintf( __( 'It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'wp-testimonial-with-widget' ), '<strong>(<em>WP Testimonials with rotator widget PRO</em>)</strong>' ).'
					</p>
					<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
				</div>';
	}
}

//Script file
require_once( WTWP_DIR . '/includes/class-wtwp-script.php' );

// Function file file
require_once( WTWP_DIR . '/includes/wtwp-functions.php' );

// Post Type file
require_once( WTWP_DIR . '/includes/wtwp-post-types.php' );

// Admin class file
require_once( WTWP_DIR . '/includes/admin/class-wtwp-admin.php' );

// Widget file file
require_once( WTWP_DIR . '/includes/widget/wp-widget-testimonials.php' );

// Templates files file file
require_once( WTWP_DIR . '/includes/shortcodes/testimonial-grid.php' );
require_once( WTWP_DIR . '/includes/shortcodes/testimonial-slider.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WTWP_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Recommended Plugins Starts */
if ( is_admin() ) {
	require_once( WTWP_DIR . '/wpos-plugins/wpos-recommendation.php' );

	wpos_espbw_init_module( array(
							'prefix'	=> 'wtwp',
							'menu'		=> 'edit.php?post_type='.WTWP_POST_TYPE,
						));
}
/* Recommended Plugins Ends */

/* Plugin Wpos Analytics Data Starts */
function wpos_analytics_anl24_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'            => 24,
							'file'          => plugin_basename( __FILE__ ),
							'name'          => 'WP Testimonials with rotator widget',
							'slug'          => 'wp-testimonials-with-rotator-widget',
							'type'          => 'plugin',
							'menu'          => 'edit.php?post_type=testimonial',
							'redirect_page'	=> 'edit.php?post_type=testimonial&page=wtwp-solutions-features',
							'text_domain'   => 'wp-testimonial-with-widget',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl24_load();
/* Plugin Wpos Analytics Data Ends */