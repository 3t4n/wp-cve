<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Box;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\Box as Models_Box;

class Get extends Base {
	protected static $route_path = 'box';

	public function callback( \WP_REST_Request $request ) {
		try {
			$models_box = Models_Box::instance();

			$box = $models_box->get();

			return $this->handle_response( $box );
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
