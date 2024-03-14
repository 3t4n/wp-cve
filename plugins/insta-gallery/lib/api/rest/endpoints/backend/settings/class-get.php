<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Settings;

use QuadLayers\IGG\Models\Setting as Models_Setting;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;

/**
 * Api_Rest_Setting_Get Class
 */

class Get extends Base {

	protected static $route_path = 'settings';

	public function callback( \WP_REST_Request $request ) {

		$models_settings = new Models_Setting();

		$settings = $models_settings->get();

		if ( null === $settings || 0 === count( $settings ) ) {
			$response = array(
				'code'    => 500,
				'message' => esc_html__( 'Unknown error', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		return $this->handle_response( $settings );
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}
}
