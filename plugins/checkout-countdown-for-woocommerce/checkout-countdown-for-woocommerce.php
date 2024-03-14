<?php
/**
 * Plugin Name:       Checkout Countdown for WooCommerce
 * Description:       A flexible WooCommerce cart/checkout countdown to help improve cart conversion.
 * Version:           3.2.1
 * Author:            Puri.io
 * Author URI:        https://puri.io/
 * Text Domain:       checkout-countdown-for-woocommerce
 * Domain Path:       /languages
 *
 * Requires PHP: 5.6
 * Requires at least: 5.0
 * WC requires at least: 5.0
 * WC tested up to: 8.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Get the value of a settings field
 *
 * @param string $option settings field name.
 * @param string $section the section name this field belongs to.
 * @param string $default default text if it's not found.
 *
 * @return mixed
 */
if ( ! function_exists( 'ccfwoo_get_option' ) ) {
	function ccfwoo_get_option( $option, $section = false, $default = '' ) {

		$section = $section === false ? 'ccfwoo_general_section' : $section;

		$options = get_option( $section );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}
		return $default;
	}
}
if ( ! function_exists( 'ccfwoo_admin_notifications' ) ) {
	function ccfwoo_admin_notifications() {

		$compatability = apply_filters( 'ccfwoo_extend_setup', array() );

		if ( isset( $compatability['pro'] ) && isset( $compatability['pro']['version'] ) ) {

			if ( version_compare( $compatability['pro']['version'], '3.0.0' ) < 0 ) {
				$class = 'notice notice-error';
				$message = __( 'Update required to Checkout Countdown Pro 3.0+ or downgrade to Checkout Countdown Free 2.4.4', 'checkout-countdown-for-woocommerce' );
				$button = '<a href="https://puri.io/blog/checkout-countdown-3-0-release-notes/" target="_blank">Read why in our release notes.</a>
';

				printf( '<div class="%1$s"><p>%2$s - %3$s</p></div>', esc_attr( $class ), esc_html( $message ), $button );
			}
		}
	}
	add_action( 'admin_notices', 'ccfwoo_admin_notifications' );
}

/**
 * WooCommerce HPOS Compatibility.
 */
add_action( 'before_woocommerce_init', function () {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

/**
 * CCFWOO_Init int the plugin.
 */
class CCFWOO_Init {
	/**
	 * Access all plugin constants
	 *
	 * @var array
	 */
	public $constants;

	/**
	 * Access notices class.
	 *
	 * @var class
	 */
	private $notices;

	/**
	 * Plugin init.
	 */
	public function __construct() {

		$this->constants = array(
			'name' => 'Checkout Countdown for WooCommerce',
			'version' => '3.2.1',
			'prefix' => 'ccfwoo',
			'admin_page' => 'checkout-countdown',
			'slug' => plugin_basename( __FILE__, ' . php' ),
			'base' => plugin_basename( __FILE__ ),
			'name_sanitized' => basename( __FILE__, '. php' ),
			'path' => plugin_dir_path( __FILE__ ),
			'url' => plugin_dir_url( __FILE__ ),
			'file' => __FILE__,
		);

		// include Notices.
		include_once plugin_dir_path( __FILE__ ) . 'classes/class-admin-notices.php';
		// Set notices to class.
		$this->notices = new ccfwoo_admin_notices();
		// Load text domain.
		add_action( 'init', array( $this, 'load_textdomain' ) );
		// Load plugin when all plugins are loaded.
		add_action( 'plugins_loaded', array( $this, 'loading' ) );
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'checkout-countdown-for-woocommerce', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Plugin init.
	 */
	public function loading() {


		// Check for older versions of Checkout Countdown.
		if ( function_exists( 'ccfwoo_setup' ) ) {
			$this->notices->add_notice(
				'warning',
				'Heads up - Checkout Countdown for WooCommerce is standalone. Please deactivate other versions of Checkout Countdown.'
			);

			return;
		}


		// Require core files.
		$enable_countdown = ccfwoo_get_option( 'enable' );

		if ( $enable_countdown === 'on' ) {
			require_once plugin_dir_path( __FILE__ ) . 'functions/functions.php';
			require_once plugin_dir_path( __FILE__ ) . 'functions/enqueue.php';
			require_once plugin_dir_path( __FILE__ ) . 'functions/shortcode.php';
		}

		require_once plugin_dir_path( __FILE__ ) . 'settings/settings.php';

		new Checkout_Countdown_Main( $this->constants );
	}

}

new CCFWOO_Init();


