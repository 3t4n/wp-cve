<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use QuadLayers\IGG\Models\Feed as Models_Feed;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;
/**
 * Api_Rest_Feeds_Get Class
 */
class Get extends Base {

	protected static $route_path = 'feeds';

	public function callback( \WP_REST_Request $request ) {

		$models_feed = new Models_Feed();

		$feed_id = $request->get_param( 'feed_id' );

		if ( null === $feed_id ) {
			$feeds = $models_feed->get();
			if ( null !== $feeds && 0 !== count( $feeds ) ) {
				return $this->handle_response( $feeds );
			}
			return $this->handle_response( array() );
		}

		$feed = $models_feed->get_by_id( $feed_id );

		if ( ! $feed ) {
			$response = array(
				'code'    => 404,
				'message' => sprintf( esc_html__( 'Feed %s not found', 'insta-gallery' ), $feed_id ),
			);
			return $this->handle_response( $response );
		}

		return $this->handle_response( $feed );

	}

	public static function get_rest_args() {
		return array(
			'feed_id' => array(
				'validate_callback' => function( $param, $request, $key ) {
					return is_numeric( $param );
				},
			),
		);
	}

	public static function get_rest_method() {
		return \WP_REST_Server::READABLE;
	}
}
