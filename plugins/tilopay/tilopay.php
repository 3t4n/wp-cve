<?php

/**
 * Plugin to conecte Tilopay payment services.
 *
 * @package  Tilopay
 */

//Function helpers hoos
use Tilopay\TilopayHelper;

/*
 * Plugin Name: Tilopay
 * Plugin URI: https://wordpress.org/plugins/tilopay/
 * Description: Accept credit and debit cards on your WooCommerce Store
 * Version: 2.1.1
 * Author:  Tilopay
 * Author URI: https://tilo.co
 * WC requires at least: 3.9
 * WC tested up to: 8.4.0
 * Tested up to: 6.4.2
 * License: GPLv2
 * Text Domain: tilopay
 * Domain Path: /languages
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

if (!defined('ABSPATH')) {
	exit;
}

define('TPAY_PLUGIN_VERSION', '2.1.1'); //set this same from changelog
define('TPAY_PLUGIN_DIR', __DIR__);
define('TPAY_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('TPAY_PLUGIN_URL', plugins_url('/', __FILE__));
define('TPAY_PLUGIN_NAME', plugin_basename(dirname(__FILE__)) . '/tilopay.php');
define('TPAY_BASE_URL', 'https://app.tilopay.com/');
define('TPAY_ENV_URL', TPAY_BASE_URL . 'api/v1/');
define('TPAY_SDK_URL', TPAY_BASE_URL . 'sdk/v2/sdk_tpay.min.js'); //sdk environment

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
	require_once dirname(__FILE__) . '/vendor/autoload.php';
}

//Woocommerce init
function tpay_woocommerce_init_tilopay_gateway() {
	if (!class_exists('WC_Payment_Gateway')) {
		return;
	}

	$tilopay_helper_hook = new TilopayHelper();

	//Register Tilopay Gateway class
	add_filter('woocommerce_payment_gateways', array($tilopay_helper_hook, 'tpay_woocommerce_register_tilopay_gateway'));

	add_filter('woocommerce_after_checkout_form', array($tilopay_helper_hook, 'tpay_filter_woocommerce_credit_card_form_fields'));

	add_action('woocommerce_order_status_changed', array($tilopay_helper_hook, 'tpay_order_status_changed'));

	/*
	// add the action
	add_action( 'woocommerce_order_partially_refunded', array( $tilopay_helper_hook, 'tpay_woocommerce_order_partially_refunded' ), 10, 2 );

	// add the action
	add_action( 'woocommerce_order_fully_refunded', array( $tilopay_helper_hook, 'tpay_woocommerce_order_fully_refunded' ), 10, 2 );

	*/
	// add the action
	add_action('woocommerce_order_refunded', array($tilopay_helper_hook, 'tpay_woocommerce_order_refunded'), 10, 2);

	/**
	 * Hook front script
	 * is located at TilopayHelper, function load_tilopay_front_scripts
	 */
	add_action('wp_enqueue_scripts', array($tilopay_helper_hook, 'load_tilopay_front_scripts'), 9999);

	/**
	 * Admin script to upload logo, only load at WC wc-settings page
	 * is located at TilopayHelper, function enqueuing_admin_config_payment_scripts
	 */
	add_action('admin_enqueue_scripts', array($tilopay_helper_hook, 'enqueuing_admin_config_payment_scripts'));

	/**
	 * Add at sub menu woocommerce
	 */
	add_action('admin_menu', array($tilopay_helper_hook, 'tpay_add_menu'));

	/**
	 * For FE call form validation
	 */
	add_action('rest_api_init', array($tilopay_helper_hook, 'register_tilopay_validation_form_route'));
}
//check if load
add_action('plugins_loaded', 'tpay_woocommerce_init_tilopay_gateway', 0);

//Declaring extension (in)compatibility with WOO HPOS
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
	}
} );

//Init hooks
if (class_exists('Tilopay\\InitTilopay')) {
	\Tilopay\InitTilopay::initHooks();
}
