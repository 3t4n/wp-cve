<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Settings;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\Settings as Models_Settings;

class Get extends Base {
	protected static $route_path = 'settings';

	public function callback( \WP_REST_Request $request ) {
		try {
			$models_settings = Models_Settings::instance();

			$settings = $models_settings->get();

			return $this->handle_response( $settings );
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
