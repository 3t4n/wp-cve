<?php
namespace QuadLayers\IGG\Api\Rest\Endpoints\Frontend;

use QuadLayers\IGG\Api\Rest\Endpoints\Base as Base;
use QuadLayers\IGG\Models\Account as Models_Account;
use QuadLayers\IGG\Api\Fetch\Business\Hashtag_Media\Get as Api_Fetch_Business_Hashtag_Media;
use QuadLayers\IGG\Utils\Cache;

class Hashtag_Media extends Base {

	protected static $route_path = 'frontend/hashtag-media';

	protected $media_cache_engine;
	protected $media_cache_key = 'feed';

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

		$after      = isset( $body['after'] ) ? $body['after'] : 0;
		$pagination = isset( $body['pagination'] ) ? $body['pagination'] : 0;

		// Get cache data and return it if exists.
		// Set prefix to cache.
		if ( ! isset( $feed['account_id'], $feed['limit'], $feed['copyright']['hide'], $feed['reel']['hide'], $feed['order_by'], $feed['tag'] ) ) {
			$message = array(
				'message' => esc_html__( 'Bad Request, feed settings attributes not setted.', 'insta-gallery' ),
				'code'    => '400',
			);
			return $this->handle_response( $message );
		}

		if ( empty( $feed['tag'] ) ) {
			$message = array(
				'message' => esc_html__( 'Feed hashtag not setted.', 'insta-gallery' ),
				'code'    => 400,
			);
			return $this->handle_response( $message );
		}

		$feed_min_data = array(
			'account_id'                => $feed['account_id'],
			'limit'                     => $feed['limit'],
			'hide_items_with_copyright' => $feed['copyright']['hide'],
			'hide_reels'                => $feed['reel']['hide'],
			'order_by'                  => $feed['order_by'],
			'tag'                       => $feed['tag'],
		);

		$feed_md5 = md5( wp_json_encode( $feed_min_data ) );

		$cache_key                = "{$this->media_cache_key}_{$feed_md5}";
		$this->media_cache_engine = new Cache( 6, true, $cache_key );

		// Get cached hashtag media data.
		$media_complete_prefix = "{$this->media_cache_key}_{$feed_md5}_{$pagination}";

		$response = $this->media_cache_engine->get( $media_complete_prefix );

		// Check if $response has data, if it have return it.
		if ( ! QLIGG_DEVELOPER && ! empty( $response['response'] ) ) {
			return $response['response'];
		}

		$account_id                = $feed['account_id'];
		$limit                     = $feed['limit'];
		$hide_items_with_copyright = $feed['copyright']['hide'];
		$hide_reels                = $feed['reel']['hide'];
		$hashtag                   = $feed['tag'];
		$order_by                  = $feed['order_by'];

		$models_account = new Models_Account();
		$account        = $models_account->get_account( $account_id );

		// Check if exist an access_token and access_token_type related to id setted by param, if it is not return error.
		if ( ! isset( $account['access_token'], $account['access_token_type'] ) ) {
			return $this->handle_response(
				array(
					'code'    => 412,
					'message' => sprintf( esc_html__( 'Account id %s not found to fetch hashtag media.', 'insta-gallery' ), $account_id ),
				)
			);
		}

		$access_token = $account['access_token'];

		// Check if access_token_type is 'BUSINESS', else return error.
		if ( $account['access_token_type'] !== 'BUSINESS' ) {
			return $this->handle_response(
				array(
					'code'    => 403,
					'message' => esc_html__( 'The account must be business to show a hashtag feed.', 'insta-gallery' ),
				)
			);
		}

		// Query to Api_Fetch_Business_Hashtag_Media.
		$business_hashtag_media = new Api_Fetch_Business_Hashtag_Media();

		// Get hashtag media data.
		$response = $business_hashtag_media->get_data( $access_token, $account_id, $hashtag, $limit, $after, $order_by, $hide_items_with_copyright, $hide_reels );

		// Check if response is an error and return it.
		if ( isset( $response['message'], $response['code'] ) ) {
			return $this->handle_response( $response );
		}

		// Update user profile data cache and return it.
		if ( ! QLIGG_DEVELOPER ) {
			$this->media_cache_engine->update( $media_complete_prefix, $response );
		}

		return $this->handle_response( $response );
	}

	public static function get_rest_args() {
		return array();
	}

	public static function get_rest_method() {
		return \WP_REST_Server::CREATABLE;
	}

	public function get_rest_permission() {
		return true;
	}
}
