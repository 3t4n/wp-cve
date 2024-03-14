<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://clickship.com
 * @since      1.0.0
 *
 * @package    Clickship
 * @subpackage Clickship/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Clickship
 * @subpackage Clickship/admin
 * @author     ClickShip <info@clickship.com>
 */
class Clickship_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = CLICKSHIP_VERSION;
		add_action( 'woocommerce_init', array( $this, 'after_wc_init' ) );

	}

	/**
	 * Hook plugin classes into WP/WC core.
	 */
	/**
	 * Woocommerce Initialization
	 */

	public function after_wc_init() {
		
		$this->attach_hooks();
	}

	/**
	 * Hook plugin classes into WP/WC core.
	 */
	/**
	 * Add WooCommerce Shipping filter and action
	 */
	public function attach_hooks() {
		
		add_filter( 'woocommerce_shipping_methods', array(&$this,'add_clickship_shipping_rates'));
		add_action( 'woocommerce_shipping_init', array(&$this,'add_clickship_shipping_rates_init'));
			
	}
	/**
	 * Add Woocommerce Shipping Method
	 *
	 * @access public
	 * @return array
	 */
	function add_clickship_shipping_rates( $methods ) {
		$methods[] = 'WC_Clickship_Shipping_Rates_Method';
		return $methods;
	}
	/**
	 *  Woocommerce Shipping Initilization
	 *
	 * @access public
	 * @return array
	 */

	function add_clickship_shipping_rates_init(){			
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-clickship-shipping.php';
	}

}