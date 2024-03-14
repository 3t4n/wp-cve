<?php
/**
 * Plugin Name: Meta Slider and Carousel with Lightbox
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/meta-slider-carousel-lightbox/
 * Description: Plugin add a gallery meta box in your post, page and create a Image gallery menu tab. Display with a lightbox. Also work with Gutenberg shortcode block.
 * Author: WP OnlineSupport, Essential Plugin
 * Text Domain: meta-slider-and-carousel-with-lightbox
 * Domain Path: /languages/
 * Version: 2.0
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/meta-slider-carousel-lightbox/
 *
 * @package Meta Slider and Carousel with Lightbox
 * @author Essential Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined( 'WP_IGSP_VERSION' ) ) {
	define( 'WP_IGSP_VERSION', '2.0' ); // Version of plugin
}

if( ! defined( 'WP_IGSP_DIR' ) ) {
	define( 'WP_IGSP_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'WP_IGSP_URL' ) ) {
	define( 'WP_IGSP_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if( ! defined( 'WP_IGSP_POST_TYPE' ) ) {
	define( 'WP_IGSP_POST_TYPE', 'wp_igsp_gallery' ); // Plugin post type
}

if( ! defined( 'WP_IGSP_META_PREFIX' ) ) {
	define( 'WP_IGSP_META_PREFIX', '_wp_igsp_' ); // Plugin metabox prefix
}

if( ! defined( 'WP_IGSP_PLUGIN_BUNDLE_LINK' ) ) {
	define( 'WP_IGSP_PLUGIN_BUNDLE_LINK','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Meta-Slider&utm_campaign=Welcome-Screen' ); // Plugin link
}

if( ! defined( 'WP_IGSP_PLUGIN_LINK_UNLOCK' ) ) {
	define( 'WP_IGSP_PLUGIN_LINK_UNLOCK','https://www.essentialplugin.com/essential-plugin-bundle-pricing/?utm_source=WP&utm_medium=Meta-Slider&utm_campaign=Features-PRO' ); // Plugin link
}

if( ! defined( 'WP_IGSP_PLUGIN_LINK_UPGRADE' ) ) {
	define( 'WP_IGSP_PLUGIN_LINK_UPGRADE','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Meta-Slider&utm_campaign=Upgrade-PRO' ); // Plugin Check link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0.0
 */
function wp_igsp_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wp_igsp_pro_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wp_igsp_pro_lang_dir = apply_filters( 'wp_igsp_languages_directory', $wp_igsp_pro_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'meta-slider-and-carousel-with-lightbox' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'meta-slider-and-carousel-with-lightbox', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WP_IGSP_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'meta-slider-and-carousel-with-lightbox', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'meta-slider-and-carousel-with-lightbox', false, $wp_igsp_pro_lang_dir );
	}

}
add_action('plugins_loaded', 'wp_igsp_load_textdomain');

/**
 * Activation Hook
 * Register plugin activation hook.
 * 
 * @since 1.0.0
 */
register_activation_hook( __FILE__, 'wp_igsp_install' );

/**
 * Deactivation Hook
 * Register plugin deactivation hook.
 * 
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'wp_igsp_uninstall');

/**
 * Plugin Setup On Activation
 * Does the initial setup, set default values for the plugin options.
 * 
 * @since 1.0.0
 */
function wp_igsp_install() {

	// Register post type function
	wp_igsp_register_post_type();

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();

	// Deactivate pro version
	if( is_plugin_active('meta-slider-and-carousel-with-lightbox-pro/frontend-gallery-slider.php') ) {
		add_action('update_option_active_plugins', 'wp_igsp_deactivate_pro_version');
	}
}

/**
 * Plugin Setup On Deactivation
 * Delete plugin options
 * 
 * @since 1.0.0
 */
function wp_igsp_uninstall() {

	// IMP need to flush rules for custom registered post type
	flush_rewrite_rules();
}

/**
 * Deactivate pro plugin
 * 
 * @since 1.1.3
 */
function wp_igsp_deactivate_pro_version() {
	deactivate_plugins('meta-slider-and-carousel-with-lightbox-pro/frontend-gallery-slider.php', true);
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.1.3
 */
function wp_igsp_admin_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = plugin_dir_path( __DIR__ ) . 'meta-slider-and-carousel-with-lightbox-pro/frontend-gallery-slider.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link		= add_query_arg( array('message' => 'wp-igsp-plugin-notice'), admin_url('plugins.php') );
	$notice_transient	= get_transient( 'wp_igsp_install_notice' );

	// If free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
		  echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'meta-slider-and-carousel-with-lightbox'), 'Meta slider and Carousel with lightbox').'</strong>.<br/>
					'.sprintf( __('It looks like you had PRO version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'meta-slider-and-carousel-with-lightbox'), 'Meta slider and Carousel with lightbox PRO' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}

// Action to display notice
add_action( 'admin_notices', 'wp_igsp_admin_notice');

// Functions File
require_once( WP_IGSP_DIR . '/includes/wp-igsp-functions.php' );

// Plugin Post Type File
require_once( WP_IGSP_DIR . '/includes/wp-igsp-post-types.php' );

// Script File
require_once( WP_IGSP_DIR . '/includes/class-wp-igsp-script.php' );

// Admin Class File
require_once( WP_IGSP_DIR . '/includes/admin/class-wp-igsp-admin.php' );

// Shortcode File
require_once( WP_IGSP_DIR . '/includes/shortcode/wp-igsp-meta-gallery-slider.php' );
require_once( WP_IGSP_DIR . '/includes/shortcode/wp-igsp-meta-gallery-carousel.php' );

// Gutenberg Block Initializer
if ( function_exists( 'register_block_type' ) ) {
	require_once( WP_IGSP_DIR . '/includes/admin/supports/gutenberg-block.php' );
}

/* Plugin Wpos Analytics Data Starts */
if( ! function_exists( 'wpos_analytics_anl39_load' ) ) {
	function wpos_analytics_anl39_load() {

		require_once dirname( __FILE__ ) . '/wpos-analytics/wpos-analytics.php';

		$wpos_analytics =  wpos_anylc_init_module( array(
								'id'			=> 39,
								'file'			=> plugin_basename( __FILE__ ),
								'name'			=> 'Meta slider and carousel with lightbox',
								'slug'			=> 'meta-slider-and-carousel-with-lightbox',
								'type'			=> 'plugin',
								'menu'			=> 'edit.php?post_type=wp_igsp_gallery',
								'redirect_page'	=> 'edit.php?post_type=wp_igsp_gallery&page=wp-igsp-solutions-features',
								'text_domain'	=> 'meta-slider-and-carousel-with-lightbox',
							));

		return $wpos_analytics;
	}

	// Init Analytics
	wpos_analytics_anl39_load();
}
/* Plugin Wpos Analytics Data Ends */