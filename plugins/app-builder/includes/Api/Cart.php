<?php

/**
 * Class Cart
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 * @author     ngocdt
 */

namespace AppBuilder\Api;

defined( 'ABSPATH' ) || exit;

use AppBuilder\Utils;

class Cart extends Base {
	public function __construct() {
		$this->namespace = APP_BUILDER_REST_BASE . '/v1';
	}

	public function register_routes() {
		if ( isset( $_REQUEST['cart_key'] ) && Utils::is_rest_api_request() ) {
			add_filter( 'woocommerce_store_api_disable_nonce_check', '__return_true' );
		}

		register_rest_route(
			$this->namespace,
			'clean-cart',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'clean_cart' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 *
	 * Delete cart by cart key
	 *
	 * @param $request
	 *
	 * @return bool
	 */
	public function clean_cart( $request ): bool {
		// Load cart.
		if ( is_null( WC()->cart ) ) {
			wc_load_cart();
		}

		// Clean cart.
		if ( ! empty( WC()->cart ) ) {
			WC()->cart->empty_cart();
		}

		return true;
	}
}
