<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds;

use QuadLayers\IGG\Models\Feed as Models_Feed;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Base as Base;
use QuadLayers\IGG\Utils\Cache as Cache;

/**
 * Api_Rest_Feeds_Clear_Cache Class
 */
class Clear_Cache extends Base {

	protected static $route_path = 'feeds/clear-cache';

	public function callback( \WP_REST_Request $request ) {

		$body = json_decode( $request->get_body(), true );

		if ( ! isset( $body['feedSettings'] ) ) {
			$message = array(
				'message' => esc_html__( 'Bad Request, feed settings not found.', 'insta-gallery' ),
				'code'    => '400',
			);
			return $this->handle_response( $message );
		}

		$feed = $body['feedSettings'];

		if ( ! isset( $feed['account_id'], $feed['limit'], $feed['copyright']['hide'], $feed['reel']['hide'], $feed['source'] ) ) {
			$message = array(
				'message' => esc_html__( 'Bad Request, feed settings attributes not setted.', 'insta-gallery' ),
				'code'    => '400',
			);
			return $this->handle_response( $message );
		}
		// Clear cache
		$feed_min_data = array(
			'account_id'                => $feed['account_id'],
			'limit'                     => $feed['limit'],
			'hide_items_with_copyright' => $feed['copyright']['hide'],
			'hide_reels'                => $feed['reel']['hide'],
		);

		if ( 'tag' === $feed['source'] ) {
			if ( ! isset( $feed['tag'] ) ) {
				$message = array(
					'message' => esc_html__( 'Bad Request, feed tag not setted.', 'insta-gallery' ),
					'code'    => '400',
				);
				return $this->handle_response( $message );
			}
			if ( ! isset( $feed['order_by'] ) ) {
				$message = array(
					'message' => esc_html__( 'Bad Request, feed order by not setted.', 'insta-gallery' ),
					'code'    => '400',
				);
				return $this->handle_response( $message );
			}
			$feed_min_data['order_by'] = $feed['order_by'];
			$feed_min_data['tag']      = $feed['tag'];
		}

		$feed_md5 = md5( wp_json_encode( $feed_min_data ) );

		$cache_key = "feed_{$feed_md5}";

		$cache_engine = new Cache( 6, true, $cache_key );

		$cache_engine->delete( $cache_engine );

		return $this->handle_response( true );
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}
}
