<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Button;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\Button as Models_Button;

class Get extends Base {
	protected static $route_path = 'button';

	public function callback( \WP_REST_Request $request ) {
		try {
			$models_button = Models_Button::instance();

			$button = $models_button->get();

			return $this->handle_response( $button );
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
