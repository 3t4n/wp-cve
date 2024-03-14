<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use QuadLayers\IGG\Models\Feed as Models_Feed;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;
use QuadLayers\IGG\Utils\Cache as Cache;


/**
 * Api_Rest_Feeds_Edit Class
 */
class Edit extends Base {

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

		$feed        = $body['feed'];
		$models_feed = new Models_Feed();

		$feeds = $models_feed->edit( $feed );

		if ( ! $feeds ) {
			$response = array(
				'code'    => 412,
				'message' => esc_html__( 'Feed cannot be updated', 'insta-gallery' ),
			);
			return $this->handle_response( $response );
		}

		return $this->handle_response( $feeds );
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::EDITABLE;
	}
}
