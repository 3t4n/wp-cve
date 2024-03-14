<?php
/**
 * Plugin Name: Product Slider and Carousel with Category for WooCommerce
 * Plugin URI: https://www.essentialplugin.com/wordpress-plugin/woo-product-slider-carousel-category/
 * Description: Display Woocommerce Product Slider/Carousel, Woocommerce Best Selling Product Slider/Carousel, Woocommerce Featured Product Slider/Carousel with category. Also work with Gutenberg shortcode block.
 * Author: WP OnlineSupport, Essential Plugin
 * Text Domain: woo-product-slider-and-carousel-with-category
 * Domain Path: /languages/
 * WC tested up to: 8.1.1
 * Version: 2.8.1
 * Author URI: https://www.essentialplugin.com/wordpress-plugin/woo-product-slider-carousel-category/
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @author Essential Plugin
 */

if( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( ! defined( 'WCPSCWC_VERSION' ) ) {
	define( 'WCPSCWC_VERSION', '2.8.1' ); // Version of plugin
}

if( ! defined( 'WCPSCWC_NAME' ) ) {
	define( 'WCPSCWC_NAME', 'Woo Product Slider and Carousel' ); // Version of plugin
}

if( ! defined( 'WCPSCWC_DIR' ) ) {
    define( 'WCPSCWC_DIR', dirname( __FILE__ ) ); // Plugin dir
}

if( ! defined( 'WCPSCWC_URL' ) ) {
    define( 'WCPSCWC_URL', plugin_dir_url( __FILE__ ) ); // Plugin url
}

if( ! defined( 'WCPSCWC_POST_TYPE' ) ) {
    define( 'WCPSCWC_POST_TYPE', 'product' ); // Plugin post type
}

if( ! defined( 'WCPSCWC_PLUGIN_BUNDLE_LINK' ) ) {
	define('WCPSCWC_PLUGIN_BUNDLE_LINK','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Product-Slider&utm_campaign=Welcome-Screen'); // Plugin link
}

if( ! defined( 'WCPSCWC_PLUGIN_LINK_UNLOCK' ) ) {
	define('WCPSCWC_PLUGIN_LINK_UNLOCK','https://www.essentialplugin.com/wordpress-plugin/woo-product-slider-carousel-category/?utm_source=WP&utm_medium=Product-Slider&utm_campaign=Features-PRO#wpos-epb'); // Plugin link
}

if( ! defined( 'WCPSCWC_PLUGIN_LINK_UPGRADE' ) ) {
	define('WCPSCWC_PLUGIN_LINK_UPGRADE','https://www.essentialplugin.com/pricing/?utm_source=WP&utm_medium=Product-Slider&utm_campaign=Upgrade-PRO#wpos-epb'); // Plugin Check link
}

if( ! defined( 'WCPSCWC_SITE_LINK' ) ) {
	define( 'WCPSCWC_SITE_LINK', 'https://www.essentialplugin.com' ); // Plugin link
}

/**
 * Load Text Domain
 * This gets the plugin ready for translation
 * 
 * @since 1.0.0
 */
function wcpscwc_load_textdomain() {

	global $wp_version;

	// Set filter for plugin's languages directory
	$wcpscwc_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
	$wcpscwc_lang_dir = apply_filters( 'wcpscwc_languages_directory', $wcpscwc_lang_dir );

	// Traditional WordPress plugin locale filter.
	$get_locale = get_locale();

	if ( $wp_version >= 4.7 ) {
		$get_locale = get_user_locale();
	}

	// Traditional WordPress plugin locale filter
	$locale = apply_filters( 'plugin_locale',  $get_locale, 'woo-product-slider-and-carousel-with-category' );
	$mofile = sprintf( '%1$s-%2$s.mo', 'woo-product-slider-and-carousel-with-category', $locale );

	// Setup paths to current locale file
	$mofile_global  = WP_LANG_DIR . '/plugins/' . basename( WCPSCWC_DIR ) . '/' . $mofile;

	if ( file_exists( $mofile_global ) ) { // Look in global /wp-content/languages/plugin-name folder
		load_textdomain( 'woo-product-slider-and-carousel-with-category', $mofile_global );
	} else { // Load the default language files
		load_plugin_textdomain( 'woo-product-slider-and-carousel-with-category', false, $wcpscwc_lang_dir );
	}
}

/**
 * Activation Hook
 * Register plugin activation hook.
 * 
 * @since 2.5
 */
register_activation_hook( __FILE__, 'wcpscwc_install' );

/**
 * Plugin Setup On Activation
 * Does the initial setup, set default values for the plugin options.
 * 
 * @since 2.5
 */
function wcpscwc_install() {

	// Deactivate free version
	if( is_plugin_active('woo-product-slider-and-carousel-with-category-pro/woo-product-slider-carousel.php') ) {
		add_action('update_option_active_plugins', 'wcpscwc_deactivate_pro_version');
	}

	// Add option for solutions & features
	add_option( 'wcpscwc_sf_optin', true );
}

/**
 * Deactivate free plugin
 * 
 * @since 2.5
 */
function wcpscwc_deactivate_pro_version() {
	deactivate_plugins('woo-product-slider-and-carousel-with-category-pro/woo-product-slider-carousel.php', true);
}

/**
 * Check WooCommerce plugin is active
 *
 * @since 1.0.0
 */
function wcpscwc_check_activation() {

	// is this plugin active?
	if ( ! class_exists('WooCommerce') && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
		
		// deactivate the plugin
 		deactivate_plugins( plugin_basename( __FILE__ ) );
 		
 		// unset activation notice
 		unset( $_GET[ 'activate' ] );
 		
 		// display notice
 		add_action( 'admin_notices', 'wcpscwc_admin_notices' );
	}
}
add_action( 'admin_init', 'wcpscwc_check_activation' );

/**
 * Admin notices
 * 
 * @since 1.0.0
 */
function wcpscwc_admin_notices() {

	if ( ! class_exists('WooCommerce') ) {
		echo '<div class="error notice is-dismissible">';
		echo sprintf( __('<p><strong>%s</strong> recommends the following plugin to use.</p>', 'woo-product-slider-and-carousel-with-category'), 'Product Slider and Carousel with Category for WooCommerce' );
		echo '<p><strong><a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> </strong></p>';
		echo '</div>';
	}
}

/**
 * Function to display admin notice of activated plugin.
 * 
 * @since 1.0.0
 */
function wcpscwc_plugin_exist_notice() {

	global $pagenow;

	// If not plugin screen
	if( 'plugins.php' != $pagenow ) {
		return;
	}

	// Check Lite Version
	$dir = plugin_dir_path( __DIR__ ) . 'woo-product-slider-and-carousel-with-category-pro/woo-product-slider-carousel.php';

	if( ! file_exists( $dir ) ) {
		return;
	}

	$notice_link		= add_query_arg( array('message' => 'wcpscwc-plugin-notice'), admin_url('plugins.php') );
	$notice_transient	= get_transient( 'wcpscwc_install_notice' );

	// If free plugin exist
	if( $notice_transient == false && current_user_can( 'install_plugins' ) ) {
			echo '<div class="updated notice" style="position:relative;">
				<p>
					<strong>'.sprintf( __('Thank you for activating %s', 'woo-product-slider-and-carousel-with-category'), 'Woo Product Slider and Carousel with Category').'</strong>.<br/>
					'.sprintf( __('It looks like you had FREE version %s of this plugin activated. To avoid conflicts the extra version has been deactivated and we recommend you delete it.', 'woo-product-slider-and-carousel-with-category'), '<strong>Woo Product Slider and Carousel with Category Pro</strong>' ).'
				</p>
				<a href="'.esc_url( $notice_link ).'" class="notice-dismiss" style="text-decoration:none;"></a>
			</div>';
	}
}

/**
 * Load the plugin after the main plugin is loaded.
 * 
 * @since 1.0.0
 */
function wcpscwc_load_plugin() {

	// Check main plugin is active or not
	if( class_exists('WooCommerce') ) {

		// Action to load plugin text domain
		wcpscwc_load_textdomain();

		// Action to display admin notice
		add_action( 'admin_notices', 'wcpscwc_plugin_exist_notice');

		// Function Files
		require_once( WCPSCWC_DIR . '/includes/wcpscwc-functions.php' );

		// Scripts Files
		require_once( WCPSCWC_DIR . '/includes/class-wcpscwc-script.php' );

		// Admin class
		require_once( WCPSCWC_DIR . '/includes/admin/class-wcpscwc-admin.php' );

		// Including some files
		require_once( WCPSCWC_DIR . '/includes/shortcodes/woo-products-slider.php' );
		require_once( WCPSCWC_DIR . '/includes/shortcodes/woo-best-selling-products-slider.php' );
		require_once( WCPSCWC_DIR . '/includes/shortcodes/woo-featured-products-slider.php' );
		require_once( WCPSCWC_DIR . '/includes/shortcodes/products-slider.php' );

		// Gutenberg Block Initializer
		if ( function_exists( 'register_block_type' ) ) {
			require_once( WCPSCWC_DIR . '/includes/admin/supports/gutenberg-block.php' );
		}

		/* Recommended Plugins Starts */
		if ( is_admin() ) {
			require_once( WCPSCWC_DIR . '/wpos-plugins/wpos-recommendation.php' );

			wpos_espbw_init_module( array(
									'prefix'	=> 'wcpscwc',
									'menu'		=> 'wcpscwc-about',
									'position'	=> 1,
								));
		}
		/* Recommended Plugins Ends */
	}
}

// Action to load plugin after the main plugin is loaded
add_action( 'plugins_loaded', 'wcpscwc_load_plugin', 5 );