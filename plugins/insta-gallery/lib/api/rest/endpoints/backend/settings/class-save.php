<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Settings;

use QuadLayers\IGG\Models\Setting as Models_Setting;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;

/**
 * Api_Rest_Setting_Save Class
 */
class Save extends Base {

	protected static $route_path = 'settings';

	public function callback( \WP_REST_Request $request ) {

		$body = $request->get_body();

		$settings = json_decode( stripslashes( $body ), true );

		if ( ! is_array( $settings ) ) {
			$response = array(
				'code'    => 412,
				'message' => esc_html__( 'Settings not saved.', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		$models_settings = new Models_Setting();

		$success = $models_settings->save( $settings );

		if ( ! $success ) {
			$response = array(
				'code'    => 412,
				'message' => esc_html__( 'Unknown error.', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		return $this->handle_response( $success );

	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}
}
