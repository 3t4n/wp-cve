<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\WooCommerce;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\WooCommerce as Models_WooCommerce;

class Post extends Base {
	protected static $route_path = 'woocommerce';

	public function callback( \WP_REST_Request $request ) {
		try {

			$body = json_decode( $request->get_body(), true );

			$woocommerce = Models_WooCommerce::instance();

			$status = $woocommerce->save( $body );

			return $this->handle_response( $status );
		} catch ( \Throwable $error ) {
			$response = array(
				'code'    => $error->getCode(),
				'message' => $error->getMessage(),
			);
			return $this->handle_response( $response );
		}
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}

	public static function get_rest_args() {
		return array();
	}
}
