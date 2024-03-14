<?php
/**
 * Intellectual Property rights, and copyright, reserved by Plug and Pay, Ltd. as allowed by law include,
 * but are not limited to, the working concept, function, and behavior of this software,
 * the logical code structure and expression as written.
 *
 * @package     TBC Checkout for WooCommerce
 * @author      Plug and Pay Ltd. http://plugandpay.ge/
 * @copyright   Copyright (c) Plug and Pay Ltd. (support@plugandpay.ge)
 * @since       1.0.0
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

namespace PlugandPay\TBC_Checkout;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TBC Checkout plugin factory class.
 */
class Plugin_Factory {

	/**
	 * __FILE__ from the root plugin file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $file;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $version;

	/**
	 * Extras / misc.
	 *
	 * @since 1.0.0
	 * @var \PlugandPay\TBC_Checkout\Extras
	 */
	public $extras;

	/**
	 * Order Actions.
	 *
	 * @since 2.0.0
	 * @var \PlugandPay\TBC_Checkout\Order_Actions
	 */
	public $order_actions;

	/**
	 * Holds a single instance of this class.
	 *
	 * @since 1.0.0
	 * @var \PlugandPay\TBC_Checkout\Plugin_Factory|null
	 */
	protected static $_instance = null;

	/**
	 * Returns a single instance of this class.
	 *
	 * @since 1.0.0
	 * @param string $file Must be __FILE__ from the root plugin file.
	 * @param string $software_version Current software version of this plugin.
	 * @return \PlugandPay\TBC_Checkout\Plugin_Factory|null
	 */
	public static function instance( $file, $software_version ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $software_version );
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @param string $file Must be __FILE__ from the root plugin file.
	 * @param string $software_version Current software version of this plugin.
	 */
	public function __construct( $file, $software_version ) {
		$this->file    = $file;
		$this->version = $software_version;

		$this->init_dependencies();

		add_filter( 'woocommerce_payment_gateways', array( $this, 'register_payment_gateway' ) );
		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Init plugin dependencies.
	 *
	 * @since 1.0.0
	 */
	public function init_dependencies() {

		/**
		 * Extras / misc.
		 *
		 * @since 1.0.0
		 * @param string $file Must be __FILE__ from the root plugin file.
		 */
		$this->extras = new \PlugandPay\TBC_Checkout\Extras( $this->file );

		/**
		 * Order Actions.
		 *
		 * @since 2.0.0
		 * @param string $software_version Current software version of this plugin.
		 */
		$this->order_actions = new \PlugandPay\TBC_Checkout\Order_Actions( $this->version );

	}

	/**
	 * Register the payment gateway.
	 *
	 * @since 1.0.0
	 * @param array $gateways Payment gateways.
	 */
	public function register_payment_gateway( $gateways ) {
		$gateways[] = new \PlugandPay\TBC_Checkout\Gateway( $this->version );
		return $gateways;
	}

	/**
	 * Load textdomain.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'tbc-checkout', false, dirname( plugin_basename( $this->file ) ) . '/languages' );
	}

}

