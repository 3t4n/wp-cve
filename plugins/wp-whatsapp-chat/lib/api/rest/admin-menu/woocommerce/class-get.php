<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\WooCommerce;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\WooCommerce as Models_WooCommerce;

class Get extends Base {
	protected static $route_path = 'woocommerce';

	public function callback( \WP_REST_Request $request ) {
		try {
			$models_woocommerce = Models_WooCommerce::instance();

			$woocommerce = $models_woocommerce->get();

			return $this->handle_response( $woocommerce );
		} catch ( \Throwable $error ) {
			$response = array(
				'code'    => $error->getCode(),
				'message' => $error->getMessage(),
			);
			return $this->handle_response( $response );
		}
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}

	public static function get_rest_args() {
		return array();
	}
}
