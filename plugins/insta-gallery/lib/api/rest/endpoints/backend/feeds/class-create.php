<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use ClosedGeneratorException;
use QuadLayers\IGG\Models\Feed as Models_Feed;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;

/**
 * Api_Rest_Feeds_Create Class
 */
class Create extends Base {

	protected static $route_path = 'feeds';

	public function callback( \WP_REST_Request $request ) {

		$body = json_decode( $request->get_body(), true );

		if ( empty( $body['feed'] ) ) {
			$response = array(
				'code'    => 412,
				'message' => esc_html__( 'Feed not setted', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		$models_feed = new Models_Feed();

		$feed = $models_feed->create( $body['feed'] );

		if ( ! $feed ) {
			$response = array(
				'code'    => 500,
				'message' => esc_html__( 'Unknown error', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		return $this->handle_response( $feed );
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}
}
