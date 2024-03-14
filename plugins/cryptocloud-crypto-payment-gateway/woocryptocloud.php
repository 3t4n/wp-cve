<?php

/*
 * Plugin Name: WooCommerce - Платежный модуль CryptoCloud
 * Description: Добавляет в WooCommerce возможность приёма платежей через платежную систему CryptoCloud
 * Plugin URI: https://app.cryptocloud.plus/integration/cms-modules/woocommerce
 * Author URI:  https://cryptocloud.plus/
 * Author: CryptoCloud
 * Version: 2.1.2
 * Text Domain: cryptocloud
 * Domain Path: /languages
*/

define('WCCC_Payment_Gateway', dirname(__FILE__));

add_action('plugins_loaded', 'init_woo_cryptocloud', 0);

function init_woo_cryptocloud() {

	if (!class_exists('WC_Payment_Gateway')) return;

	class WC_Gateway_CryptoCloud extends WC_Payment_Gateway {
		
		function __construct() {
			$this->id = 'cryptocloud';
			$this->method_title = "CryptoCloud Payment System";
			$this->method_description = "Adds the ability to accept payments via the CryptoCloud payment system to WooCommerce";

			$this->init_form_fields();
			$this->init_settings();

			$this->title = $this->get_option('title');
			$this->description = $this->get_option('description');

			if (version_compare(WOOCOMMERCE_VERSION, '2.0.0', '>=') ) {
				add_action('woocommerce_update_options_payment_gateways_' . $this->id, array(&$this, 'process_admin_options'));
			} else {
				add_action('woocommerce_update_options_payment_gateways', array(&$this, 'process_admin_options'));
			}

			add_action('woocommerce_api_wc_gateway_cryptocloud', array(&$this, 'callback'));
		}

		function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'woocommerce'),
					'type' => 'checkbox',
					'default' => 'yes',
				),
				'title' => array(
					'title' => "Name",
					'type' => 'text',
					'description' => "The name that the user sees during the payment",
					'default' => "CryptoCloud Payment System",
					'desc_tip' => true,
				),
				'description' => array(
					'title' => "Description",
					'type' => 'text',
					'description' => "The description that the user sees during the payment",
					'default' => "Accepting payment via cryptocurrency. It is possible to pay the bill using a bank card.",
					'desc_tip' => true,
				),
				'apikey' => array(
					'title' => 'API KEY',
					'type' => 'text',
				),
				'merchant_id' => array(
					'title' => __('SHOP ID','cryptocloud'),
					'type' => 'text',
				),
			);
		}
	
		function process_payment($order_id) {
			global $woocommerce;

			$order = new WC_Order($order_id);

			$amount = $order->get_total();
			$amount = str_replace(',', '.', $amount);
			$amount = number_format($amount, 2, '.', '');

			$email=$order->get_billing_email();

			$merchant_id = $this->get_option("merchant_id");
			$apikey = $this->get_option("apikey");

			$currency = get_woocommerce_currency();
			$data_request = array(
				'shop_id'	=> $merchant_id,
				'amount'	=> $amount,
				'currency'	=> $currency,
				'order_id'	=> $order_id,
				'email'		=> $email
			);
		
			$headers = array(
				'Authorization'=> "Token " . $apikey
			);

			$args = array(
				'body'        => $data_request,
				'timeout'     => '60',
				'httpversion' => '1.0',
				'headers'     => $headers,
			);
			$response = wp_remote_post('https://api.cryptocloud.plus/v1/invoice/create', $args);
			$json_data = wp_remote_retrieve_body($response);
			$json_data = json_decode($json_data, true);
			var_dump($json_data);
			$url = $json_data['pay_url'];
			$status= $json_data['status'];
			if($status=='success'){
				$woocommerce->cart->empty_cart();
			}

			return array('result' => $status, 'redirect' => $url);
		}

		public function callback() {
			if (!isset($_POST['order_id'])) exit;

			$order_id = intval($_POST['order_id']);

			$order = new WC_Order($order_id);

			$amount = $order->get_total();
			$amount = str_replace(',', '.', $amount);
			$amount = number_format($amount, 2, '.', '');
	
			$order->payment_complete();
			
			exit;
		}
	}
}

function add_woo_cryptocloud($methods) {
	$methods[] = 'WC_Gateway_CryptoCloud'; 
	return $methods;
}

add_filter('woocommerce_payment_gateways', 'add_woo_cryptocloud');