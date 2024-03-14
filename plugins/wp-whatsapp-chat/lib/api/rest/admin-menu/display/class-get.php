<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Display;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\Display as Models_Display;

class Get extends Base {
	protected static $route_path = 'display';

	public function callback( \WP_REST_Request $request ) {
		try {
			$models_display = Models_Display::instance();

			$display = $models_display->get();

			return $this->handle_response( $display );
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
