<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




final class Rest_API {


	const PREFIX = SB_CSP_PREFIX;
	const LAST_FORCE_UPDATED_OPTION_NAME = SB_CSP_PREFIX. 'last-force-updated-timestamp';

	const DEFAULT_MEDIA_COUNT = 24;
	const NAMESPACE_REST_API  = SB_CSP_PREFIX. 'api/'. STILLBE_CSP_API_VERSION;

	const FORCE_UPDATE_SHORTEST_INTERVAL = 300;   // sec


	private function __construct() {}


	// Resister REST API Endpoints
	public static function register_api() {

		// Get Users
		register_rest_route( self::NAMESPACE_REST_API, '/user(?:/(?P<id>[1-9]\d*))?', [
			array(
				'methods'  => 'GET',
			//	'callback' => __NAMESPACE__. '\Rest_API::get_users',   // 5.2.3+,
				'callback' => [ Rest_API::class, 'get_user' ],   // 5.5.0+,
				'permission_callback' => function( \WP_REST_Request $request ) { return current_user_can( 'edit_posts' ); },
			),
			array(
				'methods'  => 'POST',
			//	'callback' => __NAMESPACE__. '\Rest_API::set_users',   // 5.2.3+,
				'callback' => [ Rest_API::class, 'set_users' ],   // 5.5.0+,
				'permission_callback' => function( \WP_REST_Request $request ) { return current_user_can( 'manage_options' ); },
			),
			array(
				'methods'  => 'DELETE',
			//	'callback' => __NAMESPACE__. '\Rest_API::delete_users',   // 5.2.3+,
				'callback' => [ Rest_API::class, 'delete_users' ],   // 5.5.0+,
				'permission_callback' => function( \WP_REST_Request $request ) { return current_user_can( 'manage_options' ); },
			),
		] );

		// Get User Media
		register_rest_route( self::NAMESPACE_REST_API, '/media/(?P<id>[1-9]\d*)', [
			array(
				'methods'  => 'GET',
			//	'callback' => __NAMESPACE__. '\Rest_API::get_user_media',   // 5.2.3+,
				'callback' => [ Rest_API::class, 'get_user_media' ],   // 5.5.0+,
				'permission_callback' => '__return_true',
			),
		] );

		// Get Hashtag ID
		register_rest_route( self::NAMESPACE_REST_API, '/hashtag/convert', [
			array(
				'methods'  => 'POST',
			//	'callback' => __NAMESPACE__. '\Rest_API::convert_hashtag',   // 5.2.3+,
				'callback' => [ Rest_API::class, 'convert_hashtag' ],   // 5.5.0+,
				'permission_callback' => function( \WP_REST_Request $request ) { return current_user_can( 'edit_posts' ); },
			),
		] );

		// Update Cache Data
		register_rest_route( self::NAMESPACE_REST_API, '/media/cache-update', [
			array(
				'methods'  => 'PATCH',
			//	'callback' => __NAMESPACE__. '\Rest_API::convert_hashtag',   // 5.2.3+,
				'callback' => [ Rest_API::class, 'media_cache_update' ],   // 5.5.0+,
				'permission_callback' => '__return_true',
			),
		] );

	}


	public static function get_user( \WP_REST_Request $request ) {

		// Authorizaed ID from Endpoint Parameter
		$id = $request->get_param( 'id' );

		// GET Parameters
		$params = $request->get_query_params();

		// Get Invalid Accounts
		$is_getting_invalid = ! empty( $params['all'] );

		// Settings
		$_setting = get_option( Setting::SETTING_NAME, array() );
		$accounts = $_setting['accounts'] ?? [];

		$_accounts = array();
		foreach( $accounts as $_account ) {
			if( empty( $_account->id ) ) {
				continue;
			}
			$is_valid = ! ( empty( $_account->api_type ) || empty( $_account->token->token ) || empty( $_account->me->id ) || empty( $_account->me->username ) );
			if( ! $is_getting_invalid && ! $is_valid ) {
				continue;
			}
			if( ! empty( $_account->profile_picture_id ) ) {
				$img_url = wp_get_attachment_image_url( $_account->profile_picture_id, 'thumbnail' );
			} elseif( ! empty( $_account->profile_picture_url ) && false === strpos( $_account->profile_picture_url, 'data:image/gif;' ) ) {
				$img_url = $_account->profile_picture_url;
			} else {
				$img_url = STILLBE_CSP_BASE_URL. '/asset/img/ig-icon.png';
			}
			$_accounts[ (int) $_account->id ] = (object) array(
				'api'    => $_account->api_type ?? esc_html__( 'Unknown', 'still-be-combine-social-photos' ),
				'user'   => $_account->me->username ?? esc_html__( 'Unknown', 'still-be-combine-social-photos' ),
				'name'   => empty( $_account->name ) ? ( empty( $_account->me->name ) ? ( $_account->me->username ?? esc_html__( 'Unknown', 'still-be-combine-social-photos' ) ) : $_account->me->name ) : $_account->name,
				'img'    => esc_url( $img_url ),
				'active' => $is_valid,
			);
		}

		// Authorized ID is Empty
		if( empty( $id ) ) {
			return new \WP_REST_Response( array(
				'ok'   => true,
				'data' => $_accounts,
			) );
		}

		// Select an Account using an Authorized ID
		if( isset( $_accounts[ (int) $id ] ) ) {
			$response = new \WP_REST_Response( array(
				'ok'   => true,
				'data' => $_accounts[ (int) $id ],
			) );
		} else {
			$response = new \WP_REST_Response( array(
				'ok'      => false,
				'data'    => new \stdClass,
				'message' => sprintf( esc_html__( 'ID = %d is not found....', 'still-be-combine-social-photos' ), (int) $id ),
			) );
			$response->set_status( 404 );
		}

		return $response;

	}


	public static function set_users( \WP_REST_Request $request ) {
		return null;
	}
	public static function delete_users( \WP_REST_Request $request ) {
		return null;
	}


	public static function get_user_media( \WP_REST_Request $request ) {

		// Authorizaed ID from Endpoint Parameter
		$id = $request->get_param( 'id' );

		// GET Parameters
		$params = $request->get_query_params();

		// Settings
		$_setting = get_option( Setting::SETTING_NAME, array() );
		$accounts = $_setting['accounts'] ?? [];

		// Select an Account using an Authorized ID
		$account = null;
		foreach( (array) $accounts as $_account ) {
			if( $id == $_account->id ) {
				$account = $_account;
				break;
			}
		}

		// ID is not Found...
		if( empty( $account->token->token ) || empty( $account->token->api ) || empty( $account->me->id ) ) {

			$error = array(
				'ok'      => false,
				'code'    => 0,
				'id'      => $id,
			//	'account' => $accounts,
				'message' => esc_html__( 'ID is not found. Please check ID.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 404 );

			return $response;

		}

		$get_media_count = $params['media_count'] ?? self::DEFAULT_MEDIA_COUNT;
		$getting_api     = self::_get_api_class( $account->token->api );

		if( empty( $getting_api ) ) {
			return [];
		}

		$media_data = $getting_api::get_media_data( $account->me->id, $account->token->token, $get_media_count );

		$data = $media_data->data ?? null;
		$user = $media_data->user ?? null;

		$ok = isset( $data );

		return new \WP_REST_Response( compact( 'ok', 'data', 'user') );

	}


	public static function convert_hashtag( \WP_REST_Request $request ) {

		// Params from Endpoint Parameter
		$id      = $request->get_param( 'user_id' );
		$hashtag = $request->get_param( 'hashtag' );

		if( empty( $hashtag ) || empty( $id ) ) {

			$error = array(
				'ok'      => false,
				'message' => esc_html__( 'There are no hashtags to convert or no user ID.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 401 );

			return $response;
		}

		// Settings
		$_setting = get_option( Setting::SETTING_NAME, array() );
		$accounts = $_setting['accounts'] ?? [];

		// Select an Account using an Authorized ID
		$account = null;
		foreach( (array) $accounts as $_account ) {
			if( $id == $_account->id ) {
				$account = $_account;
				break;
			}
		}

		// ID is not Found...
		if( empty( $account->token->token ) || empty( $account->token->api ) || empty( $account->me->id ) ) {

			$error = array(
				'ok'      => false,
				'code'    => 0,
				'id'      => $id,
				'message' => esc_html__( 'ID is not found. Please check ID.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 401 );

			return $response;

		}

		$getting_api = self::_get_api_class( $account->token->api );

		if( empty( $getting_api ) || ! method_exists( $getting_api, 'hashtag_search' ) ) {

			$error = array(
				'ok'      => false,
				'code'    => 1,
				'id'      => $id,
				'api'     => $getting_api,
				'message' => esc_html__( 'Hashtag Search is not Available....', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 401 );

			return $response;

		}

		// Search Hashtag ID from Hashtag String
		$hashtag_id = $getting_api::hashtag_search( $account->me->id, $account->token->token, $hashtag );

		// Select an Account using an Authorized ID
		if( empty( $hashtag_id ) ) {
			$response = new \WP_REST_Response( array(
				'ok'      => false,
				'code'    => 2,
				'api'     => $getting_api,
				'hashtag' => $hashtag,
				'message' => sprintf( esc_html__( 'Hashtag #%s is not found....', 'still-be-combine-social-photos' ), $hashtag ),
				'test'=>[$hashtag_id,$account->me->id, $account->token->token, $hashtag],
			) );
		} else {
			$response = new \WP_REST_Response( array(
				'ok'         => true,
				'api'        => $getting_api,
				'hashtag'    => $hashtag,
				'hashtag_id' => $hashtag_id,
				'message'    => sprintf( esc_html__( 'Successfully converted the hashtag #%s!!', 'still-be-combine-social-photos' ), $hashtag ),
			) );
		}

		return $response;

	}


	public static function media_cache_update( \WP_REST_Request $request ) {

		// Params from Endpoint Parameter
		$get_body_json   = $request->get_json_params();
		$account_id      = $get_body_json['account_id']      ?? 0;
		$get_media_count = $get_body_json['get_media_count'] ?? self::DEFAULT_MEDIA_COUNT;
		$fields          = $get_body_json['fields']          ?? [];
		$advanced        = $get_body_json['advanced']        ?? null;
		$permalink       = $get_body_json['permalink']       ?? '';

		if( empty( $account_id ) ) {

			$error = array(
				'ok'      => false,
				'code'    => 0,
				'message' => esc_html__( 'There are no Account ID.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 401 );

			return $response;

		}

		// Get last execution timestamp
		$last_force_updated = absint( get_option( self::LAST_FORCE_UPDATED_OPTION_NAME. '-id'. $account_id, 0 ) );

		if( self::FORCE_UPDATE_SHORTEST_INTERVAL > time() - $last_force_updated ) {

			$error = array(
				'ok'      => false,
				'code'    => 2,
				'message' => esc_html__( 'The interval is too short since the last update.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );

			return $response;

		}

		// Settings
		$_setting = get_option( Setting::SETTING_NAME, array() );
		$accounts = $_setting['accounts'] ?? [];

		// Select an Account using an Authorized ID
		$account = null;
		foreach( (array) $accounts as $_account ) {
			if( $account_id == $_account->id ) {
				$account = $_account;
				break;
			}
		}

		// ID is not Found...
		if( empty( $account->token->token ) || empty( $account->token->api ) || empty( $account->me->id ) ) {

			$error = array(
				'ok'      => false,
				'code'    => 4,
				'id'      => $account_id,
			//	'account' => $accounts,
				'message' => esc_html__( 'ID is not found. Please check ID.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 404 );

			return $response;

		}

		$getting_api = self::_get_api_class( $account->token->api );

		if( empty( $getting_api ) ) {

			$error = array(
				'ok'      => false,
				'code'    => 6,
				'id'      => $account_id,
				'api'     => $account->token->api,
				'message' => esc_html__( 'No executable API found.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 404 );

			return $response;

		}

		// Instagram User Info
		$user_id      = $account->me->id;
		$access_token = $account->token->token;

		// Calculate the number of posts to be get
		// Cache data rounded up to a multiple of the maximum number of posts that can be get at one time
		$media_count = ceil( $get_media_count / $getting_api::MEDIA_GET_COUNT_AT_A_TIME ) * $getting_api::MEDIA_GET_COUNT_AT_A_TIME;

		// Fields
		if( empty( $fields ) ) {
			$fields = $getting_api::DEFAULT_GET_MEDIA_FIELDS;
		}

		// Advanced
		if( is_array( $advanced ) ) {
			$advanced = (object) $advanced;
		}

		// Set API type so that cache data is not covered
		$api_type = $getting_api::API_TYPE;

		// Request Params
		$params = (object) compact( 'user_id', 'access_token', 'media_count', 'fields', 'advanced', 'api_type' );

		// Debug
	//	error_log( 'update_ig_rest: '. json_encode( $params ) );

		// Others of Getting Recent Hashtag
		if( empty( $advanced->hashtag_recent ) ) {

			// Update Cache
		//	$getting_api::update_ig_media_cache( $params );
			$result = $getting_api::get_media_data( $user_id, $access_token, $media_count, $fields, $advanced, true );

			// Save last execution time
			update_option( self::LAST_FORCE_UPDATED_OPTION_NAME. '-id'. $account_id, time(), 'no' );

			// Done!!
			$success = array(
				'ok'      => true,
				'result'  => $result,
				'message' => esc_html__( 'Done!!', 'still-be-combine-social-photos' ),
				'request' => compact( 'media_count', 'fields', 'advanced', 'api_type' ),
			);

			$response = new \WP_REST_Response( $success );

			return $response;

		}

		// Recent Hashtag
		// Convert a permalink to a thumbnail URL for recent posts related to hashtag, as posts older than the last 24 hours cannot be got

		// Get Media URL
		$new_media_url = '';

		// Convert a Post Permalink to its Media URL
		add_action( 'requests-requests.before_request', function( $location ) use( &$new_media_url ) {
			$new_media_url = $location;
		} );
		wp_remote_get( $permalink. 'media?size=l' );   // Image is acquired but not used

		// When permalink could not be converted to media URL
		if( empty( $new_media_url ) || ! preg_match( '$https://[^\.\/]+\.cdninstagram\.com/$', $new_media_url ) ) {

			$error = array(
				'ok'        => false,
				'code'      => 9,
				'id'        => $account_id,
				'permalink' => $permalink,
				'media_url' => $new_media_url,
				'message'   => esc_html__( 'Could not convert permalink to media URL.', 'still-be-combine-social-photos' ),
			);

			$response = new \WP_REST_Response( $error );
			$response->set_status( 404 );

			return $response;

		}


		// Convert parameter to string
		$param_serialized = $getting_api::_param_serialized( $params );
		$hash_md5         = $getting_api::_param_hash_md5( $params );

		// Cache data
		$cached_key   = self::PREFIX. 'cached-ig-media-'. $hash_md5;
		$_cached_data = @json_decode( get_option( $cached_key, '{}' ) );
		$cached_data  = $_cached_data->cache->$param_serialized ?? new \stdClass;

		// Updated Object
		$updated_obj = clone $cached_data;
		if( empty( $updated_obj->data ) ) {
			$updated_obj->data = [];
		}

		// Update a Media URL
		$is_execute_update = false;
		foreach( $updated_obj->data as $index => $post ) {
			if( $permalink === $post->permalink ) {
				$updated_obj->data[ $index ]->media_url = $new_media_url;
				$is_execute_update = true;
			}
		}

		// Update Cache Data
		$is_updated = null;
		if( $is_execute_update ) {
			$_cached_data->cache->$param_serialized = $updated_obj;
			$is_updated = update_option( $cached_key, json_encode( $_cached_data ) );
		}


		// Save last execution time
		update_option( self::LAST_FORCE_UPDATED_OPTION_NAME. '-id'. $account_id, time(), 'no' );

		// Done!!
		$success = array(
			'ok'        => true,
			'permalink' => $permalink,
			'media_url' => $new_media_url,
			'message'   => esc_html__( 'Done!!', 'still-be-combine-social-photos' ),
			'request'   => compact( 'media_count', 'fields', 'advanced', 'api_type' ),
		);

		$response = new \WP_REST_Response( $success );

		return $response;

	}


	private static function _get_api_class( $type ) {

		if( 'ig_basic_display' === $type ) {
			return __NAMESPACE__. '\Basic_Display_API';
		}

		if( 'ig_graph' === $type ) {
			return __NAMESPACE__. '\Graph_API';
		}

		return null;

	}


}




