<?php
/**
 * Plugin Name:       Secondary Product Image for WooCommerce
 * Plugin URI:        https://www.wpzoom.com/plugins/
 * Description:       Secondary Product Image for WooCommerce adds a hover effect that will reveal a secondary product thumbnail to product images on your WooCommerce product listings.
 * Version:           1.0.2
 * Requires at least: 5.7
 * Requires PHP:      7.2
 * Author:            WPZOOM
 * Author URI:        https://www.wpzoom.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       secondary-product-image-for-woocommerce
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WPZOOM_WC_SPI_VER', get_file_data( __FILE__, [ 'Version' ] )[0] ); // phpcs:ignore

define( 'WPZOOM_WC_SPI__FILE__', __FILE__ );
define( 'WPZOOM_WC_SPI_ABSPATH', dirname( __FILE__ ) . '/' );
define( 'WPZOOM_WC_SPI_PLUGIN_BASE', plugin_basename( WPZOOM_WC_SPI__FILE__ ) );
define( 'WPZOOM_WC_SPI_PLUGIN_DIR', dirname( WPZOOM_WC_SPI_PLUGIN_BASE ) );

define( 'WPZOOM_WC_SPI_PATH', plugin_dir_path( WPZOOM_WC_SPI__FILE__ ) );
define( 'WPZOOM_WC_SPI_URL', plugin_dir_url( WPZOOM_WC_SPI__FILE__ ) );

// Instance the plugin
WPZOOM_WC_Secondary_Product_Image::instance();

/**
 * Main WPZOOM_WC_Secondary_Product_Image Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
class WPZOOM_WC_Secondary_Product_Image {

	/**
	 * Instance
	 *
	 * @var WPZOOM_WC_Secondary_Product_Image The single instance of the class.
	 * @since 1.0.0
	 * @access private
	 * @static
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 * @return WPZOOM_WC_Secondary_Product_Image An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'metabox' ) );

		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
		add_action( 'plugins_loaded', array( $this, 'frontend' ) );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'secondary-product-image-for-woocommerce', false, WPZOOM_WC_SPI_PLUGIN_DIR . '/languages' );
	}

	/**
	 * Includes frontend files
	 * @method frontend
	 *
	 * @return void
	 */
	public function frontend() {
		
		include_once WPZOOM_WC_SPI_PATH . 'includes/wpzoom-wc-spi-frontend.php';
	}


	/**
	 * Includes metabox files
	 * @method metabox
	 *
	 * @return void
	 */
	public function metabox() {
		include_once WPZOOM_WC_SPI_PATH . 'includes/wpzoom-wc-spi-metabox.php';

	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if WooCoomerce has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function on_plugins_loaded() {
		if( ! $this->is_woocommerce_activated() ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );	
		}
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have WooCommerce installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$plugin = 'woocommerce/woocommerce.php';
		$installed_plugins = get_plugins();

		$is_woocommerce_installed = isset( $installed_plugins[ $plugin ] );

		if ( $is_woocommerce_installed ) {
		
			$message = sprintf(
				/* translators: 1: Plugin name 2: WooCommerce */
				esc_html__( '"%1$s" requires "%2$s" to be activated.', 'secondary-product-image-for-woocommerce' ),
				'<strong>' . esc_html__( 'WooCommerce Secondary Product Image', 'secondary-product-image-for-woocommerce' ) . '</strong>',
				'<strong>' . esc_html__( 'WooCommerce', 'secondary-product-image-for-woocommerce' ) . '</strong>'
			);

			$button_text = esc_html__( 'Activate WooCommerce', 'secondary-product-image-for-woocommerce' );
			$button_link = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
		
		} else {

			$message = sprintf(
				/* translators: 1: Plugin name 2: WooCommerce */
				esc_html__( '"%1$s" requires "%2$s" to be installed.', 'secondary-product-image-for-woocommerce' ),
				'<strong>' . esc_html__( 'WooCommerce Secondary Product Image', 'secondary-product-image-for-woocommerce' ) . '</strong>',
				'<strong>' . esc_html__( 'WooCommerce', 'secondary-product-image-for-woocommerce' ) . '</strong>'
			);

			$button_text = esc_html__( 'Install WooCommerce', 'secondary-product-image-for-woocommerce' );
			$button_link = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );

		}

		$button = sprintf(
			/* translators: 1: Button URL 2: Button text */
			'<a class="button button-primary" href="%1$s">%2$s</a>',
			esc_url( $button_link ),
			esc_html( $button_text )
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p> <p>%2$s</p></div>', $message, $button );

	}

	/**
	 * Check if the WooCommerce is activated.
	 */
	public function is_woocommerce_activated() {
		return class_exists( 'WooCommerce' ) ? true : false;
	}

}