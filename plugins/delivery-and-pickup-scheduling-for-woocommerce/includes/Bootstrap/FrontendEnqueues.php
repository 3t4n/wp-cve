<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://uriahsvictor.com
 * @since      1.0.0
 *
 * @package    Lpac_DPS
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Lpac_DPS
 * @author_name    Uriahs Victor <info@soaringleads.com>
 */
namespace Lpac_DPS\Bootstrap;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lpac_DPS\Controllers\Checkout_Page\SetupFields as Fields_Setup_Controller;

/**
 * Class responsible for loading frontend styles and scripts.
 *
 * @package Lpac_DPS\Bootstrap
 * @since 1.0.0
 */
class FrontendEnqueues {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = LPAC_DPS_PLUGIN_NAME;
		$this->version     = LPAC_DPS_VERSION;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lpac_DPS_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lpac_DPS_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'public/css/lpac-dps-public.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . '-flatpickr', LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'public/css/lib/flatpickr.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Lpac_DPS_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Lpac_DPS_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$path = ( LPAC_DPS_DEBUG ) ? '' : 'build/';
		$time = ( LPAC_DPS_DEBUG ) ? time() : '';

		wp_enqueue_script( $this->plugin_name, LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'public/js/' . $path . 'lpac-dps-public.js', array( 'jquery' ), $this->version . $time, false );
		if ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
			wp_enqueue_script( $this->plugin_name . '-flatpickr', LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'public/js/lib/flatpickr.min.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-checkout-page-scripts', LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'public/js/' . $path . 'checkout/page.js', array( 'jquery', 'wp-util' ), $this->version . $time, false );
			wp_enqueue_script( $this->plugin_name . '-checkout-page-flatpickr-scripts', LPAC_DPS_PLUGIN_ASSETS_PATH_URL . 'public/js/' . $path . 'checkout/flatpickr-related.js', array( 'jquery', 'wp-util' ), $this->version . $time, false );
		}

		$this->add_inline_scripts();
	}

	/**
	 * Compile our inline scripts that should be available on frontend.
	 *
	 * @return void
	 */
	private function add_inline_scripts(): void {

		$controller_checkout_page_fields_setup = new Fields_Setup_Controller();

		wp_add_inline_script( $this->plugin_name . '-checkout-page-scripts', $controller_checkout_page_fields_setup->getJSConfig(), 'before' );

		// -------------
		// Output plugin version to console
		// -------------
		$plugin_type = ( dps_fs()->can_use_premium_code() ) ? 'PRO' : 'Free';

		wp_add_inline_script(
			$this->plugin_name,
			"
			console.log('Chwazi - Delivery & Pickup Scheduling for WooCommerce {$plugin_type}: v{$this->version}');
			"
		);
		// --------------
		// --------------
	}
}
