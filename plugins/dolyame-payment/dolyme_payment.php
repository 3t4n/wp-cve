<?php
/**
 * Plugin Name: Dolyame payment
 * Plugin URI: https://wordpress.org/plugins/dolyame-payment
 * Description: Dolyame Payments Gateway for WooCommerce
 * Version: 3.1.0
 * Author: «Долями»
 * Author URI: https://dolyame.ru
 * Copyright: © 2021, «Долями».
 * Text Domain: dolyame_payment
 * Domain Path: /languages
 */

class WC_Dolyamepayment
{
	private $settings;
	private $plugin_page;

	public function __construct()
	{
		$this->settings = get_option('woocommerce_dolyamepayment_settings');

		if (empty($this->settings['login'])) {
			$this->settings['login'] = '';
		}
		if (empty($this->settings['password'])) {
			$this->settings['password'] = '';
		}

		if (empty($this->settings['enabled'])) {
			$this->settings['enabled'] = 'no';
		}

		add_action('plugins_loaded', [$this, 'initGateway']);
	}

	public function initGateway()
	{
		if (!class_exists('WC_Payment_Gateway')) {
			return;
		}

		add_action('before_woocommerce_init', [$this, 'declareBlockSupport']);
		add_action( 'woocommerce_blocks_loaded', [$this, 'registerBlock'] );

		load_plugin_textdomain('dolyame_payment', false, dirname(plugin_basename(__FILE__)) . '/languages/');

		include_once __DIR__ . '/includes/class-wc-gateway-dolyamepayment.php';

		add_filter('woocommerce_payment_gateways', [$this, 'addGateway']);

		if ($this->settings['enabled'] == 'no') {
			return;
		}

		// Disable for subscriptions until supported
		if (!is_admin() && class_exists('WC_Subscriptions_Cart') && WC_Subscriptions_Cart::cart_contains_subscription() && 'no' === get_option(WC_Subscriptions_Admin::$option_prefix . '_accept_manual_renewals', 'no')) {
			return;
		}

	}

	public function addGateway($methods)
	{
		$methods[] = 'WC_Gateway_dolyamepayment';
		return $methods;
	}

	public function declareBlockSupport()
	{
		if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
		// Declare compatibility for 'cart_checkout_blocks'
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__, true);
		}
	}

	public function registerBlock() {
	// Check if the required class exists
		if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			return;
		}

		// Include the custom Blocks Checkout class
		require_once __DIR__ . '/includes/class-dolyame-gateway-blocks.php';

		// Hook the registration function to the 'woocommerce_blocks_payment_method_type_registration' action
		add_action(
			'woocommerce_blocks_payment_method_type_registration',
			function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
				// Register an instance of My_Custom_Gateway_Blocks
				$payment_method_registry->register( new Dolyame_Gateway_Blocks );
			}
		);
	}

}
$GLOBALS['wc_dolyamepayment'] = new WC_dolyamepayment();
