<?php
/**
 * Sets up and defines the PeachPay rest api endpoints.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * The PeachPay routes API for JS-PHP interaction.
 */
class PeachPay_Routes_Manager {
	/**
	 * Magic constructor method is called on instantiation. Automagically registers all of our endpoints.
	 */
	public function __construct() {
		// Load any custom utilities we may need.
		require_once PEACHPAY_ABSPATH . 'core/util/button.php';

		// Load endpoint files.
		require_once PEACHPAY_ABSPATH . 'core/routes/order-payment-status.php';
		require_once PEACHPAY_ABSPATH . 'core/routes/ocu-product-data.php';

		// wc-ajax endpoints need initialized right away.
		add_action( 'wc_ajax_pp-ocu-product', 'peachpay_wc_ajax_ocu_product_data' );
		add_action( 'wc_ajax_pp-get-modal-currency-data', array( $this, 'peachpay_wc_ajax_modal_currency_of_country' ) );

		add_action( 'rest_api_init', array( $this, 'peachpay_rest_api_init' ) );
	}

	/**
	 * Load external rest api files and register api endpoints.
	 */
	public function peachpay_rest_api_init() {
		register_rest_route(
			PEACHPAY_ROUTE_BASE,
			'/health',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'handle_health_request' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			PEACHPAY_ROUTE_BASE,
			'/order/status',
			array(
				'methods'             => 'POST',
				'callback'            => 'peachpay_rest_api_order_payment_status',
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Health endpoint.
	 */
	public function handle_health_request() {
		PeachPay_Capabilities::refresh();

		return array(
			'plugin_version' => PEACHPAY_VERSION,
			'plugin_mode'    => peachpay_is_test_mode() ? 'test' : 'live',
		);
	}

	/**
	 * Handles a get request, provided a country, responds with the currency code.
	 */
	public function peachpay_wc_ajax_modal_currency_of_country() {
		try {
			$headers = getallheaders();
			$data    = isset( $headers['Currency-Country'] ) ? peachpay_currencies_to_modal_from_country( $headers['Currency-Country'] ) : peachpay_currencies_to_modal_from_country( peachpay_get_client_country() );

			wp_send_json(
				array(
					'success' => true,
					'data'    => $data,
				)
			);
		} catch ( Exception $error ) {
			wp_send_json(
				array(
					'success'       => false,
					'error_message' => $error->getMessage(),
					'notices'       => wc_get_notices(),
				)
			);
		}

		wp_die();
	}
}
