<?php

namespace QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Box;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Base;
use QuadLayers\QLWAPP\Models\Box as Models_Box;

class Post extends Base {
	protected static $route_path = 'box';

	public function callback( \WP_REST_Request $request ) {
		try {

			$body = json_decode( $request->get_body(), true );

			$models_box = Models_Box::instance();

			$status = $models_box->save( $body );

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
