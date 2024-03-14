<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Scheme;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\Scheme as Models_Scheme;

class Get extends Base {
	protected static $route_path = 'scheme';

	public function callback( \WP_REST_Request $request ) {
		try {
			$models_scheme = Models_Scheme::instance();

			$scheme = $models_scheme->get();

			return $this->handle_response( $scheme );
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
