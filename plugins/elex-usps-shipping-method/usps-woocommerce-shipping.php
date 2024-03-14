<?php
/*
	Plugin Name: ELEX USPS Shipping Method for WooCommerce(Basic)
	Plugin URI: https://elextensions.com/plugin/woocommerce-usps-shipping-plugin-with-print-label/
	Description: Obtain real time Shipping Rates via the USPS Shipping API.
	Version: 2.0.1
	Author: ELEXtensions
	WC requires at least: 2.6
	WC tested up to: 8.2
	Author URI: https://elextensions.com/
	https://www.usps.com/webtools/htm/Rate-Calculators-v1-5.htm
	https://www.usps.com/business/web-tools-apis/delivery-confirmation-domestic-shipping-label-api.htm
*/

if ( ! defined( 'ELEX_USPS_SHIPPING_BASIC_PLUGIN_DIR_PATH' ) ) {
	define( 'ELEX_USPS_SHIPPING_BASIC_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ELEX_USPS_SHIPPING_BASIC_PLUGIN_DIR_URL' ) ) {
	define( 'ELEX_USPS_SHIPPING_BASIC_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}
function elex_usps_basic_pre_activation_check() {
	if ( is_plugin_active( 'usps-woocommerce-shipping/usps-woocommerce-shipping.php' ) ) {
		deactivate_plugins( basename( __FILE__ ) );
		wp_die( wp_kses_post( "Oops! You tried installing the basic version without deactivating and deleting the premium version. Kindly deactivate and delete the premium version and then try again. For any issues, raise a ticket via <a target='_blank' href='https://elextensions.com/support/'>Elex Support</a>", 'wf-usps-woocommerce-shipping' ), '', array( 'back_link' => 1 ) );
	}
}

register_activation_hook( __FILE__, 'elex_usps_basic_pre_activation_check' );
if ( ! defined( 'ELEX_USPS_ID' ) ) {
	define( 'ELEX_USPS_ID', 'wf_shipping_usps' );
}
$activated_plugins = (array) get_option( 'active_plugins' );
if ( is_multisite() ) {
	$activated_plugins = array_merge( $activated_plugins, get_site_option( 'active_sitewide_plugins' ), array() );
}


	/** 
		 * Fire a filter hook to Check if WooCommerce is active
		 *
		 * @since 2002
		 * @param $package
		 */  
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || ( array_key_exists( 'woocommerce/woocommerce.php', $activated_plugins ) ) ) {

	/**
	 * WC_USPS class
	 */
	if ( ! class_exists( 'ELEX_USPS_WooCommerce_Shipping' ) ) { 
		class ELEX_USPS_WooCommerce_Shipping {
			/**
			 * Constructor
			 */
			public function __construct() {
				add_action( 'init', array( $this, 'init' ) );
				add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
				add_action( 'woocommerce_shipping_init', array( $this, 'shipping_init' ) );
				add_filter( 'woocommerce_shipping_methods', array( $this, 'add_method' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ) );
			}

			/**
			 * Localisation
			 */
			
			public function init() {
				if ( ! class_exists( 'ELEX_Order' ) ) {
					include_once 'includes/class-wf-legacy.php';
				}       
			}


			/**
			 * Plugin page links
			 */
			public function plugin_action_links( $links ) {
				$plugin_links = array(
					'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=elex_shipping_usps' ) . '">' . __( 'Settings', 'wf-usps-woocommerce-shipping' ) . '</a>',

					'<a href="https://elextensions.com/plugin/woocommerce-usps-shipping-plugin-with-print-label/" target="_blank">' . __( 'Premium Upgrade', 'wf-usps-woocommerce-shipping' ) . '</a>',

					'<a href="https://elextensions.com/support/" target="_blank">' . __( 'Support', 'wf-usps-woocommerce-shipping' ) . '</a>',
				);
				return array_merge( $plugin_links, $links );
			}

			/**
			 * Load gateway class
			 */
			public function shipping_init() {
				include_once  'includes/class-elex-shipping-usps.php' ;
			}

			/**
			 * Add method to WC
			 */
			public function add_method( $methods ) {
				$methods[] = 'ELEX_Shipping_USPS';
				return $methods;
			}

			/**
			 * Enqueue scripts
			 */
			public function scripts() {
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'common-script', plugins_url( '/resources/wf_common.js', __FILE__ ), array( 'jquery' ), '1.0' );
				wp_enqueue_style( 'wf-common-style', plugins_url( '/resources/wf_common_style.css', __FILE__ ), array(), '1.0' );
			}
		}
		new ELEX_USPS_WooCommerce_Shipping();
	}

	// High performance order tables compatibility.
	add_action(
		'before_woocommerce_init',
		function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} 
	);

	// review component
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once  ABSPATH . 'wp-admin/includes/plugin.php';
	}
	include_once __DIR__ . '/review_and_troubleshoot_notify/review-and-troubleshoot-notify-class.php';
	$data                      = get_plugin_data( __FILE__ );
	$data['name']              = $data['Name'];
	$data['basename']          = plugin_basename( __FILE__ );
	$data['rating_url']        = 'https://elextensions.com/plugin/elex-woocommerce-usps-shipping-plugin-with-print-label-free-version/#reviews';
	$data['documentation_url'] = 'https://elextensions.com/knowledge-base/set-up-elex-woocommerce-usps-shipping-plugin-with-print-label/';
	$data['support_url']       = 'https://wordpress.org/support/plugin/elex-usps-shipping-method/';

	new \Elex_Review_Components( $data );
}
