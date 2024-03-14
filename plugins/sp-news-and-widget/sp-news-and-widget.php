<?php
/**
* Plugin Name: WP News and Scrolling Widgets
* Plugin URL: https://www.essentialplugin.com/wordpress-plugin/sp-news-and-scrolling-widgets/
* Text Domain: sp-news-and-widget
* Domain Path: /languages/
* Description: A simple News and three widgets(static, scrolling and with thumbs) plugin. Also work with Gutenberg shortcode block.
* Version: 4.9
* Author: WP OnlineSupport, Essential Plugin
* Author URI: https://www.essentialplugin.com/wordpress-plugin/sp-news-and-scrolling-widgets/
* Contributors: WP OnlineSupport
*
* @author Essential Plugin
* @package WP News and Scrolling Widgets
*/

if( ! defined( 'WPNW_VERSION' ) ) {
	define( 'WPNW_VERSION', '4.9' ); // Version of plugin
}
if( ! defined( 'WPNW_DIR' ) ) {
	define( 'WPNW_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'WPNW_URL' ) ) {
	define( 'WPNW_URL', plugin_dir_url( __FILE__ ) ); // Plugin URL
}
if( ! defined( 'WPNW_POST_TYPE' ) ) {
	define( 'WPNW_POST_TYPE', 'news' ); // Plugin post type
}
if( ! defined( 'WPNW_CAT' ) ) {
	define( 'WPNW_CAT', 'news-category' ); // Plugin Category
}
if( ! defined( 'WPNW_SITE_LINK' ) ) {
	define('WPNW_SITE_LINK','https://www.essentialplugin.com'); // Plugin link
}
if( ! defined( 'WPNW_PLUGIN_BUNDLE_LINK' ) ) {
	define('WPNW_PLUGIN_BUNDLE_LINK','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=News&utm_campaign=Welcome-Screen'); // Plugin link
}
if( ! defined( 'WPNW_PLUGIN_LINK_UNLOCK' ) ) {
	define('WPNW_PLUGIN_LINK_UNLOCK','https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=News&utm_campaign=Features-PRO'); // Plugin link
}
if( ! defined( 'WPNW_PLUGIN_LINK_UPGRADE' ) ) {
	define('WPNW_PLUGIN_LINK_UPGRADE','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=News&utm_campaign=Upgrade-PRO'); // Plugin Check link
}

/**
 * Load Text Domain and do stuff once all plugin is loaded
 * This gets the plugin ready for translation
 * 
 * @since 1.0.0
 */
function wpnw_news_load_textdomain() {
	
	global $wp_version;

	// Set filter for plugin's languages directory
	$wpnw_pro_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wpnw_pro_lang_dir = apply_filters( 'wpnw_news_languages_directory', $wpnw_pro_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'sp-news-and-widget' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'sp-news-and-widget', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WPNW_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'sp-news-and-widget', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'sp-news-and-widget', false, $wpnw_pro_lang_dir );
	}
}
add_action( 'plugins_loaded', 'wpnw_news_load_textdomain' );

/**
 * Activation Hook
 * 
 * Register plugin activation hook.
 *
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wpnw_install' );

/**
 * Deactivation Hook
 * 
 * Register plugin deactivation hook.
 * 
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wpnw_uninstall');

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @since 1.0.0
 */
function wpnw_install() {

	//post type and taxonomies function
	wpnw_register_post_type();
	wpnw_register_taxonomies();

	// IMP to call to generate new rules
	flush_rewrite_rules();

	if( is_plugin_active('wp-news-and-widget-pro/sp-news-and-widget.php') ) {
		 add_action('update_option_active_plugins', 'wpnw_deactivate_pro_version');
	}
}

/**
 * Plugin Functinality (On Deactivation)
 * 
 * Delete  plugin options.
 * 
 * @since 1.0.0
 */
function wpnw_uninstall() {

	// IMP to call to generate new rules
	flush_rewrite_rules();
}

/**
 * Deactivate free plugin
 * 
 * @since 1.0.0
 */
function wpnw_deactivate_pro_version() {
   deactivate_plugins('wp-news-and-widget-pro/sp-news-and-widget.php',true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.0.0
 */
function wpnw_news_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = WP_PLUGIN_DIR . '/wp-news-and-widget-pro/sp-news-and-widget.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link			= add_query_arg( array('message' => 'wpnw-plugin-notice'), admin_url('plugins.php') );
	$notice_transient		= get_transient( 'wpnw_install_notice' );

	// If free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
			echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'sp-news-and-widget'), 'WP News and three widgets').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'sp-news-and-widget'), '<strong>(<em>WP News and three widgets PRO</em>)</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}
add_action( 'admin_notices', 'wpnw_news_admin_notice');

// Functions file
require_once( WPNW_DIR . '/includes/wpnw-functions.php' );

// Regrister Post Type
require_once( WPNW_DIR . '/includes/wpnw-post-types.php' );

// Script File
require_once( WPNW_DIR . '/includes/class-wpnw-script.php' );

// Admin Class File
require_once( WPNW_DIR . '/includes/admin/class-wpnw-admin.php' );

// Shortcode file
require_once( WPNW_DIR . '/includes/shortcode/sp-news-shortcode.php' );

// Widget file
require_once( WPNW_DIR . '/includes/widgets/wpnw-widgets.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WPNW_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Recommended Plugins Starts */
if ( is_admin() ) {
	require_once( WPNW_DIR . '/wpos-plugins/wpos-recommendation.php' );

	wpos_espbw_init_module( array(
							'prefix'		=> 'wpnw',
							'menu'		=> 'edit.php?post_type='.WPNW_POST_TYPE,
							'position'	=> 5,
						));
}
/* Recommended Plugins Ends */

/* Plugin Analytics Data */
function wpos_analytics_anl20_load() {

	require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

	$wpos_analytics =  wpos_anylc_init_module( array(
							'id'			=> 20,
							'file'			=> plugin_basename( __FILE__ ),
							'name'			=> 'WP News and Scrolling Widgets',
							'slug'			=> 'wp-news-and-scrolling-widgets',
							'type'			=> 'plugin',
							'menu'			=> 'edit.php?post_type=news',
							'redirect_page'=> 'edit.php?post_type=news&page=wpnw-solutions-features',
							'text_domain'	=> 'sp-news-and-widget',
						));

	return $wpos_analytics;
}

// Init Analytics
wpos_analytics_anl20_load();
/* Plugin Analytics Data Ends */