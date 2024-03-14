<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




final class Basic_Display_API {


	use Instagram_API_Common_Method;


	const PREFIX = SB_CSP_PREFIX;

	const IG_GRAPH_API_EXAMINE_TOKEN_ME_URL = 'https://graph.instagram.com/'. STILLBE_CSP_FB_GRAPH_API_VERSION. '/me';
	const IG_GRAPH_API_USER_MEDIA_URL       = 'https://graph.instagram.com/'. STILLBE_CSP_FB_GRAPH_API_VERSION. '/{user-id}/media';
	const IG_GRAPH_API_REFRESH_TOKEN_URL    = 'https://graph.instagram.com/refresh_access_token';

	const DEFAULT_GET_ACCOUNT_FIELDS        = [ 'account_type', 'id', 'media_count', 'username' ];
	const DEFAULT_GET_MEDIA_FIELDS          = [ 'caption', 'id', 'media_type', 'media_url', 'permalink', 'thumbnail_url', 'timestamp', 'username', 'children{media_type,media_url,thumbnail_url}' ];

	const ACTION_HOOK_GET_IG_MEDIA          = SB_CSP_PREFIX. 'getting-ig-media-basic';

	const MEDIA_GET_COUNT_AT_A_TIME         = 100;   // Max = 100, Default = 25
	const MEDIA_GET_MAX_COUNT_AT_A_TIME     = 100;
	const MEDIA_GET_INTERVAL_BASE           = 20;

	const API_REQUEST_TIMEOUT               = 30;

	const API_TYPE                          = 'ig_basic_display';


	// Account Information from Access Token
	public static function get_me( $access_token, $dummy = null, $fields = [] ) {

		if( empty( $access_token ) ) {
			return null;
		}

		if( empty( $fields ) || ! is_array( $fields ) ) {
			$fields = self::DEFAULT_GET_ACCOUNT_FIELDS;
		}

		$query = array(
			'fields'       => implode( ',', $fields ),
			'access_token' => $access_token,
		);

		$uri = add_query_arg(
			$query,
			self::IG_GRAPH_API_EXAMINE_TOKEN_ME_URL
		);

		$response_me = wp_remote_get( $uri );
		$json_me     = wp_remote_retrieve_body( $response_me );
		$code_me     = wp_remote_retrieve_response_code( $response_me );

		if( is_wp_error( $json_me ) || 200 > $code_me || 300 <= $code_me ) {
			return null;
		}

		$me = @json_decode( $json_me );

		return $me;

	}


	// Refresh Token
	public static function refresh_token( $account ) {

		if( empty( $account->token->token ) ) {
			return null;
		}

		$query = array(
			'grant_type'   => 'ig_refresh_token',
			'access_token' => $account->token->token,
		);

		$uri = add_query_arg(
			$query,
			self::IG_GRAPH_API_REFRESH_TOKEN_URL
		);

		$response_refresh = wp_remote_get( $uri );
		$json_refresh     = wp_remote_retrieve_body( $response_refresh );
		$code_refresh     = wp_remote_retrieve_response_code( $response_refresh );

		if( is_wp_error( $json_refresh ) || 200 > $code_refresh || 300 <= $code_refresh ) {
			return null;
		}

		$refresh = @json_decode( $json_refresh );

		return $refresh;

	}


	// Get User Media Helper
	private static function _get_media_data( $params ) {

		$user_media_url = str_replace( '{user-id}', $params->user_id, self::IG_GRAPH_API_USER_MEDIA_URL );

		$query = array(
			'access_token' => $params->access_token,
			'fields'       => implode( ',', $params->fields ),
			'limit'        => self::MEDIA_GET_COUNT_AT_A_TIME,
		);

		$user_media_uri = add_query_arg(
			$query,
			$user_media_url
		);

		// @since 0.8.0
		$is_exclude_video = ! empty( $params->advanced->exclude_video );

		$data  = [];
		$next  = $params->next ?? $user_media_uri;
		$error = null;

		do{

			// Request API
			$response = wp_remote_get( $next, array( 'timeout' => self::API_REQUEST_TIMEOUT ) );
			$json     = wp_remote_retrieve_body( $response );
			$code     = wp_remote_retrieve_response_code( $response );

			$next = null;

			if( is_wp_error( $json ) || 200 > $code || 300 <= $code ) {
				$error = $response;
				break;
			}

			$obj = @json_decode( $json );

			if( empty( $obj->data ) || ! is_array( $obj->data ) ) {
				$error = $response;
				break;
			}

			// @since 0.8.0
			foreach( $obj->data as $d ) {
				$media_type = 'CAROUSEL_ALBUM' === $d->media_type ? ( $d->children->data[0]->media_type ?? 'UNKOWN' ) : $d->media_type;
				if( $is_exclude_video && 'VIDEO' === $media_type ) {
					continue;
				}
				array_push( $data, $d );
			}

		//	array_push( $data, ...$obj->data );

			$next = $obj->paging->next ?? null;

			if( $params->media_count <= count( $data ) ) {
				break;
			}

		} while( $next );

		$data = array_slice( $data, 0, $params->media_count );

		return (object) compact( 'data', 'next', 'error' );

	}


}



