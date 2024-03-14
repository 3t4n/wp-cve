<?php

/**
 * class Checkout
 *
 * @link       https://appcheap.io
 * @since      3.1.0
 * @author     ngocdt
 */

namespace AppBuilder\Api;

use AppBuilder\AppBuilderCartSessionHandler;

defined( 'ABSPATH' ) || exit;

class Checkout extends Base {

	private $gateways = array(
		'paystack'               => 'AppBuilder\Gateway\PayStackGateway',
		'paytabs_all'            => 'AppBuilder\Gateway\PayTabsGateway',
		'myfatoorah_v2'          => 'AppBuilder\Gateway\MyFatoorahV2Gateway',
		'rave'                   => 'AppBuilder\Gateway\FlutterWaveGateway\FlutterWaveGateway',
		'woo-mercado-pago-basic' => 'AppBuilder\Gateway\MercadopagoGateway',
		'vnpay'                  => 'AppBuilder\Gateway\VnpayGateway',
		'razorpay'               => 'AppBuilder\Gateway\RazopayGateway',
		'hyperpay'               => 'AppBuilder\Gateway\HyperpayGateway',
		'hyperpay_mada'          => 'AppBuilder\Gateway\HyperpayGateway',
		'hyperpay_applepay'      => 'AppBuilder\Gateway\HyperpayGateway',
		'hyperpay_stcpay'        => 'AppBuilder\Gateway\HyperpayGateway',
		'hyperpay_tabby'         => 'AppBuilder\Gateway\HyperpayGateway',
		'hyperpay_zoodpay'       => 'AppBuilder\Gateway\HyperpayGateway',
		'iyzico'                 => 'AppBuilder\Gateway\IyzicoGateway',
	);

	public function __construct() {
		$this->namespace = constant( 'APP_BUILDER_REST_BASE' ) . '/v1';

		// Init hooks and filters Razopay.
		if ( class_exists( 'WC_Payment_Gateway' ) ) {
			new \AppBuilder\Gateway\RazopayGateway();
		}

		// Init hooks and filters Iyzico.
		if ( class_exists( 'Iyzico_Checkout_For_WooCommerce_Gateway' ) ) {
			new \AppBuilder\Gateway\IyzicoGateway();
		}
	}

	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'confirm-payment',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'confirm_payment' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public function confirm_payment( $request ) {

		$this->load_cart( $request );

		$gateway = $request->get_param( 'gateway' );
		$action  = $request->get_param( 'action' );

		if ( $action == 'clean' ) {
			return rest_ensure_response( $this->clean_cart( $request ) );
		}

		if ( empty( $gateway ) ) {
			return new \WP_Error(
				'app_builder_payment_confirm',
				__( 'The payment id not exits.', 'app-builder' ),
				array(
					'status' => 403,
				)
			);
		}

		if ( isset( $this->gateways[ $gateway ] ) ) {
			$class = new $this->gateways[ $gateway ]();

			return rest_ensure_response( $class->confirm_payment( $request ) );
		}

		return new \WP_Error(
			'app_builder_payment_confirm',
			__( 'The payment not implement yet.', 'app-builder' ),
			array(
				'status' => 403,
			)
		);
	}

	public function clean_cart( $request ): array {
		global $woocommerce;
		$this->load_cart( $request );

		/** Check cart not empty clean cart */
		if ( ! empty( $woocommerce->cart ) ) {
			$woocommerce->cart->empty_cart();
		}

		return array(
			'redirect' => 'order',
		);
	}

	public function load_cart( $request ) {
		defined( 'WC_ABSPATH' ) || exit;

		include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
		include_once WC_ABSPATH . 'includes/class-wc-cart.php';

		if ( is_null( WC()->cart ) ) {
			wc_load_cart();
		}
	}
}
