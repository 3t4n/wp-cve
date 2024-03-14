<?php
/**
 * Plugin Name: Blog Designer - Post and Widget
 * Plugin URL: https://www.essentialplugin.com/wordpress-plugin/blog-designer-post-and-widget/
 * Description: Display Post on your website with 2 designs(Grid and Slider) with 1 widget. Also work with Gutenberg shortcode block.
 * Version: 2.7
 * Author: WP OnlineSupport, Essential Plugin
 * Text Domain: blog-designer-for-post-and-widget
 * Domain Path: /languages/
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/blog-designer-post-and-widget/
 *
 * @package Blog Designer - Post and Widget
 * @author WP OnlineSupport, Essential Plugin
*/

/**
 * Basic plugin definitions
 * 
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined( 'BDPW_VERSION' ) ) {
	define( 'BDPW_VERSION', '2.7' ); // Version of plugin
}
if( ! defined( 'BDPW_DIR' ) ) {
	define( 'BDPW_DIR', dirname( __FILE__ ) ); // Plugin dir
}
if( ! defined( 'BDPW_URL' ) ) {
	define( 'BDPW_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}
if( ! defined( 'BDPW_PLUGIN_BASENAME' ) ) {
	define( 'BDPW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) ); // Plugin base name
}
if( ! defined( 'BDPW_POST_TYPE' ) ) {
	define( 'BDPW_POST_TYPE', 'post' ); // Post type name
}
if( ! defined( 'BDPW_CAT' ) ) {
	define( 'BDPW_CAT', 'category' ); // Plugin category name
}
if( ! defined( 'BDPW_PLUGIN_BUNDLE_LINK' ) ) {
	define( 'BDPW_PLUGIN_BUNDLE_LINK', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Blog-Designer&utm_campaign=Welcome-Screen' ); // Plugin link
}
if( ! defined( 'BDPW_PLUGIN_LINK_UNLOCK' ) ) {
	define( 'BDPW_PLUGIN_LINK_UNLOCK', 'https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Blog-Designer&utm_campaign=Features-PRO' ); // Plugin link
}
if( ! defined( 'BDPW_PLUGIN_LINK_UPGRADE' ) ) {
	define( 'BDPW_PLUGIN_LINK_UPGRADE', 'https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Blog-Designer&utm_campaign=Upgrade-PRO' ); // Plugin Check link
}
if( ! defined( 'BDPW_SITE_LINK' ) ) {
	define( 'BDPW_SITE_LINK', 'https://www.essentialplugin.com' ); // Plugin link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0
 */
function bdpw_load_textdomain() {
	
	global $wp_version;

	// Set filter for plugin's languages directory
	$bdpw_lang_dir = dirname( BDPW_PLUGIN_BASENAME ) . '/languages/';
	$bdpw_lang_dir = apply_filters( 'bdpw_languages_directory', $bdpw_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'blog-designer-for-post-and-widget' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'blog-designer-for-post-and-widget', $locale );

	// Setup paths to current locale file
	$mofile_global = WP_LANG_DIR . '/plugins/' . basename( BDPW_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'blog-designer-for-post-and-widget', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'blog-designer-for-post-and-widget', false, $bdpw_lang_dir );
	}
}

// Action to load plugin text domain
add_action('plugins_loaded', 'bdpw_load_textdomain');

/**
 * Activation Hook
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'bdpw_install' );

/**
 * Deactivation Hook
 * Register plugin deactivation hook.
 * 
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'bdpw_uninstall');

/**
 * Plugin Activation Function
 * Does the initial setup, sets the default values for the plugin options
 * 
 * @since 1.0.0
 */
function bdpw_install() {

	// Deactivate Pro Version
	if( is_plugin_active('blog-designer-for-post-and-widget-pro/blog-designer-post-and-widget.php') ) {
		add_action('update_option_active_plugins', 'bdpw_deactivate_pro_version');
	}
}

/**
 * Deactivate premium version of plugin
 * 
 * @since 1.0
 */
function bdpw_deactivate_pro_version() {
	deactivate_plugins('blog-designer-for-post-and-widget-pro/blog-designer-post-and-widget.php', true);
}

/**
 * Plugin Deactivation Function
 * Delete plugin options and etc
 * 
 * @since 1.0.0
 */
function bdpw_uninstall() {
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.0
 */
function bdpw_plugin_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = WP_PLUGIN_DIR . '/blog-designer-for-post-and-widget-pro/blog-designer-post-and-widget.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link 		= add_query_arg( array('message' => 'bdpw-plugin-notice'), admin_url('plugins.php') );
	$notice_transient 	= get_transient( 'bdpw_install_notice' );

	// If PRO plugin is active and free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
		echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'blog-designer-for-post-and-widget'), 'Blog Designer - Post and Widget').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'blog-designer-for-post-and-widget'), '<strong>Blog Designer - Post and Widget PRO</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
		</div>';
	}
}

// Action to display notice
add_action( 'admin_notices', 'bdpw_plugin_admin_notice');

// Functions file
require_once( BDPW_DIR . '/includes/bdpw-functions.php' );

// Script Class File
require_once( BDPW_DIR . '/includes/class-bdpw-script.php' );

// Admin Class File
require_once( BDPW_DIR . '/includes/admin/class-bdpw-admin.php' );

// Shortcode File
require_once( BDPW_DIR . '/includes/shortcode/wpsp-post.php' );
require_once( BDPW_DIR . '/includes/shortcode/wpsp-recent-post-slider.php' );

// Widget File
require_once( BDPW_DIR . '/includes/widget/latest-post-widget.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( BDPW_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Recommended Plugins Starts */
if ( is_admin() ) {
	require_once( BDPW_DIR . '/wpos-plugins/wpos-recommendation.php' );

	wpos_espbw_init_module( array(
							'prefix'	=> 'bdpw',
							'menu'		=> 'bdpw-about',
							'position'	=> 1,
						));
}
/* Recommended Plugins Ends */

/* Plugin Wpos Analytics Data Starts */
if( ! function_exists( 'bdpw_analytics_anl_load' ) ) {
	function bdpw_analytics_anl_load() {

		require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

		$wpos_analytics =  wpos_anylc_init_module( array(
								'id' 			=> 27,
								'file'			=> plugin_basename( __FILE__ ),
								'name'			=> 'Blog Designer - Post and Widget',
								'slug'			=> 'blog-designer-post-and-widget',
								'type'			=> 'plugin',
								'menu'			=> 'bdpw-about',
								'redirect_page'	=> 'bdpw-solutions-features',
								'text_domain'	=> 'blog-designer-for-post-and-widget',
							));

		return $wpos_analytics;
	}

	// Init Analytics
	bdpw_analytics_anl_load();
}
/* Plugin Wpos Analytics Data Ends */